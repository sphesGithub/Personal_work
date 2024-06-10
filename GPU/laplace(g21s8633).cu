#include <stdlib.h>
#include <stdio.h>
#include <math.h>
#include <cuda_runtime.h>
//NEW libraries to handle timing
#include <Winsock2.h>
#include <stdint.h> 
#include <Windows.h>


// size of plate
#define COLUMNS 1000
#define ROWS 1000

#ifndef MAX_ITER
#define MAX_ITER 100
#endif

// largest permitted change in temp (This value takes about 3400 steps)
#define MAX_TEMP_ERROR 0.01


int gettimeofday(struct timeval * tp, struct timezone * tzp)
{
	//NEW code taken from Stack Overflow to calculate timings on Windows
	static const uint64_t EPOCH = ((uint64_t)116444736000000000ULL);

	SYSTEMTIME  system_time;
	FILETIME    file_time;
	uint64_t    time;

	GetSystemTime(&system_time);
	SystemTimeToFileTime(&system_time, &file_time);
	time = ((uint64_t)file_time.dwLowDateTime);
	time += ((uint64_t)file_time.dwHighDateTime) << 32;

	tp->tv_sec = (long)((time - EPOCH) / 10000000L);
	tp->tv_usec = (long)(system_time.wMilliseconds * 1000);
	return 0;
}

// Kernel function to initialize the temperature array
__global__ void initialize(double *Temperature_last) {
    int i = blockIdx.y * blockDim.y + threadIdx.y;
    int j = blockIdx.x * blockDim.x + threadIdx.x;

    // Initialize the interior and boundary of the grid
    if (i <= ROWS + 1 && j <= COLUMNS + 1) {
        // Initialize interior to 0.0
        Temperature_last[i * (COLUMNS + 2) + j] = 0.0;

        // Set left and right boundaries
        if (j == 0) {
            Temperature_last[i * (COLUMNS + 2) + j] = 0.0;
        }
        if (j == COLUMNS + 1) {
            Temperature_last[i * (COLUMNS + 2) + j] = (100.0 / ROWS) * i;
        }

        // Set top and bottom boundaries
        if (i == 0) {
            Temperature_last[i * (COLUMNS + 2) + j] = 0.0;
        }
        if (i == ROWS + 1) {
            Temperature_last[i * (COLUMNS + 2) + j] = (100.0 / COLUMNS) * j;
        }
    }
}

__device__ double atomicMax(double* address, double val) {
    
    /**
     * I found this function one
     *reads the 32-bit or 64-bit word old located at the address address in global or shared memory, computes the maximum of old and val, 
     *and stores the result back to memory at the same address. 
     *These three operations are performed in one atomic transaction. The function returns old.
     *link:https://docs.nvidia.com/cuda/cuda-c-programming-guide/index.html?highlight=atomicMax#atomicmax
    */
    unsigned long long int* address_as_ull = (unsigned long long int*)address;
    unsigned long long int old = *address_as_ull, assumed;

    do {
        assumed = old;
        old = atomicCAS(address_as_ull, assumed,
                        __double_as_longlong(fmax(val, __longlong_as_double(assumed))));
    } while (assumed != old);

    return __longlong_as_double(old);
}
//Kernel function to calculate the max difference
// Computes the maximum temperature change (error) between the current and previous iterations, and updates the previous temperature array.
__global__ void max_dt(double *Temp, double *Temp_last, double *d_max_error) {
    int i = blockIdx.y * blockDim.y + threadIdx.y + 1;
    int j = blockIdx.x * blockDim.x + threadIdx.x + 1;

    double local_max_error = 0.0;

    if (i <= ROWS && j<=COLUMNS) {
        double diff = fabs(Temp[i * (COLUMNS + 2) + j] - Temp_last[i * (COLUMNS + 2) + j]);
        local_max_error = diff;
        Temp_last[i * (COLUMNS + 2) + j] = Temp[i * (COLUMNS + 2) + j];
    }

    // Use atomicMax to update the global maximum error
    atomicMax(d_max_error, local_max_error);
}

//kernerl function to calculate the laplace
__global__ void laplace(double *Temp,double *Temp_last)
{
    int i = blockIdx.y * blockDim.y + threadIdx.y;
    int j = blockIdx.x * blockDim.x + threadIdx.x;

        // main calculation: average my four neighbors
        if (i <= ROWS + 1 && j <= COLUMNS + 1)
        {
            Temp[i * (COLUMNS + 2) + j]=0.25 * (Temp_last[(i+1) * (COLUMNS + 2) + j] + Temp_last[(i-1) * (COLUMNS + 2) + j] +
                                            Temp_last[i * (COLUMNS + 2) + (j+1)] + Temp_last[i * (COLUMNS + 2) + (j-1)]);
        }
        
}

// print diagonal in bottom right corner where most action is

int main(int argc, char const *argv[])
{

    struct timeval start_time, stop_time;  		 // timers
    int max_iterations = MAX_ITER;

    dim3 blockDim(16, 16);                                                                               // Define thread block dimensions
    dim3 gridDim((COLUMNS + 2 + blockDim.x - 1) / blockDim.x, (ROWS + 2 + blockDim.y - 1) / blockDim.y); // Define grid dimensions

    // Allocate memory on the host and device for Temperature_last
    double *Temperature_last_host = (double *)malloc(sizeof(double) * (ROWS + 2) * (COLUMNS + 2));
    double *Temperature_last_dev;
    cudaMalloc((void **)&Temperature_last_dev, sizeof(double) * (ROWS + 2) * (COLUMNS + 2));

    // Initialize Temperature_last on the device
    initialize<<<gridDim, blockDim>>>(Temperature_last_dev);
    cudaDeviceSynchronize(); // Ensure kernel execution is complete

    // Copy Temperature_last from device to host
    cudaMemcpy(Temperature_last_host, Temperature_last_dev, sizeof(double) * (ROWS + 2) * (COLUMNS + 2), cudaMemcpyDeviceToHost);


    double *Temperature_host = (double *)malloc(sizeof(double) * (ROWS + 2) * (COLUMNS + 2));
    double *Temperature_dev;
    
    cudaMalloc((void **)&Temperature_dev, sizeof(double) * (ROWS + 2) * (COLUMNS + 2));

    int iteration=1;                                     // current iteration
    double dt=100;  
    double *d_max_error;
    cudaMalloc((void **)&d_max_error, sizeof(double));
    double max_error = 0.0;


    // do until error is minimal or until max steps
    while ( dt > MAX_TEMP_ERROR && iteration <= max_iterations ) {
    
        // Initialize Temperature_last on the device
        laplace<<<gridDim, blockDim>>>(Temperature_dev,Temperature_last_dev);
        cudaDeviceSynchronize(); // Ensure kernel execution is complete
        dt = 0.0; // reset largest temperature change
        max_dt<<<gridDim, blockDim>>>(Temperature_dev, Temperature_last_dev, d_max_error);
        cudaDeviceSynchronize();

        cudaMemcpy(&max_error, d_max_error, sizeof(double), cudaMemcpyDeviceToHost);
        dt = max_error;

	    iteration++;
    }
    

    // Copy Temperature_last from device to host
    cudaMemcpy(Temperature_host, Temperature_dev, sizeof(double) * (ROWS + 2) * (COLUMNS + 2), cudaMemcpyDeviceToHost);

    bool viewOriginal = true;// please change the value to false to see the full plate
    if(viewOriginal){
        int i;
    printf("---------- Iteration number: %d ------------\n", iteration-1);
    for(i = ROWS-5; i <= ROWS; i++) {
        printf("[%d,%d]: %5.2f  ", i, i, Temperature_host[i * (COLUMNS + 2) + i]);
    }
    printf("\n");

    }
    else{

    printf("Final temperature---------------------------------------------------\n\n");
     for (int i = ROWS-5; i <= ROWS ; i++)
    {
        for (int j = COLUMNS-5; j <= COLUMNS ; j++)
        {
            printf("[%d,%d]: %5.2f  ", i, j, Temperature_host[i * (COLUMNS + 2) + j]);
        }
        printf("\n");
    }

    }

     gettimeofday(&stop_time,NULL);
    float diff = fabs(( (stop_time.tv_sec-start_time.tv_sec)*1000000 + (stop_time.tv_usec - start_time.tv_usec) )/1000000.0);
        
    printf("\nMax error at iteration %d was %f\n", iteration-1, dt);
    printf("Total time was %f seconds\n", diff);
    
      // Free allocated memory
    free(Temperature_last_host);
    free(Temperature_host);
    cudaFree(Temperature_last_dev);
    cudaFree(Temperature_dev);
    cudaFree(d_max_error);
    return 0;
}
