import matplotlib.pyplot as plt
import numpy as np
from sklearn import svm
from sklearn.metrics import accuracy_score
from sklearn.datasets import make_moons


#my script is was constructed with the help of the plot_iris_exercise on scikit-learn
# link: https://scikit-learn.org/stable/auto_examples/exercises/plot_iris_exercise.html#sphx-glr-auto-examples-exercises-plot-iris-exercise-py
def svm_boundary(X, y, kernel_type='rbf' ):
    # fit the model
    clf = svm.SVC(kernel=kernel_type, C=10000000, gamma='scale')
    clf.fit(X, y)

    # Calculate accuracy
    y_pred = clf.predict(X)
    accuracy = accuracy_score(y, y_pred)

    # Plot the decision boundary
    plt.figure(figsize=(10, 6))
    plt.clf()

    plt.scatter(
        clf.support_vectors_[:, 0],
        clf.support_vectors_[:, 1],
        s=80,
        facecolors="none",
        zorder=10,
        edgecolors="k",
        cmap=plt.get_cmap("RdBu"),
        label='Support Vectors'
    )
    plt.scatter(
        X[:, 0], X[:, 1], c=y, zorder=10, cmap=plt.get_cmap("RdBu"), edgecolors="k", label='Data Points'
    )

    plt.axis("tight")
    # Create meshgrid for decision boundary visualization
    x_min, x_max = X[:, 0].min() - 0.1, X[:, 0].max() + 0.1
    y_min, y_max = X[:, 1].min() - 0.1, X[:, 1].max() + 0.1

    XX, YY = np.mgrid[x_min:x_max:500j, y_min:y_max:500j]
    Z = clf.decision_function(np.c_[XX.ravel(), YY.ravel()])

    # Put the result into a contour plot
    Z = Z.reshape(XX.shape)
    plt.contour(XX, YY, Z, colors='k', levels=[-1, 0, 1], alpha=0.5, linestyles=['--', '-', '--'])

    plt.xlim(x_min, x_max)
    plt.ylim(y_min, y_max)

    plt.xticks(())
    plt.yticks(())
    
    plt.title('SVM Decision Boundary {} and Accuracy {:.2f}'.format(kernel_type, accuracy))
    plt.xlabel('Feature 1')
    plt.ylabel('Feature 2')

    plt.show()

# Example usage:
n_samples = 500
n_features = 2
# -------------------------------------------------------------------------------------------------------------------
# Exercise 1
X1 = np.random.rand(n_samples, n_features)
y1 = np.ones((n_samples, 1))
idx_neg = (X1[:, 0] - 0.5) ** 2 + (X1[:, 1] - 0.5) ** 2 < 0.03
y1[idx_neg] = 0

# -------------------------------------------------------------------------------------------------------------------
# Exercise 2
X2 = np.random.rand(n_samples, n_features)
y2 = np.ones((n_samples, 1))
idx_neg = (X2[:, 0] < 0.5) * (X2[:, 1] < 0.5) + (X2[:, 0] > 0.5) * (X2[:, 1] > 0.5)
y2[idx_neg] = 0


# -------------------------------------------------------------------------------------------------------------------
# Exercise 3
rho_pos = np.random.rand(n_samples // 2, 1) / 2.0 + 0.5
rho_neg = np.random.rand(n_samples // 2, 1) / 4.0
rho = np.vstack((rho_pos, rho_neg))
phi_pos = np.pi * 0.75 + np.random.rand(n_samples // 2, 1) * np.pi * 0.5
phi_neg = np.random.rand(n_samples // 2, 1) * 2 * np.pi
phi = np.vstack((phi_pos, phi_neg))
X3 = np.array([[r * np.cos(p), r * np.sin(p)] for r, p in zip(rho, phi)])
y3 = np.vstack((np.ones((n_samples // 2, 1)), np.zeros((n_samples // 2, 1))))

print("exercise 3")
print(X3.shape)
print(y3.shape)
X3 = X3.reshape(-1, 2)
y3 = y3.reshape(-1)
print(X3.shape)
print(y3.shape)
# -------------------------------------------------------------------------------------------------------------------
# Exercise 4
rho_pos = np.linspace(0, 2, n_samples // 2)
rho_neg = np.linspace(0, 2, n_samples // 2) + 0.5
rho = np.vstack((rho_pos, rho_neg))
phi_pos = 2 * np.pi * rho_pos
phi = np.vstack((phi_pos, phi_pos))
X4 = np.array([[r * np.cos(p), r * np.sin(p)] for r, p in zip(rho, phi)])
y4 = np.vstack((np.ones((n_samples // 2, 1)), np.zeros((n_samples // 2, 1))))

print("exercise 4")
print(X4.shape)
print(y4.shape)


X4 = np.c_[np.reshape(X4[:, 0],-1), np.reshape(X4[:, 1],-1)]
y4 = np.reshape(y4,-1)
print(X4.shape)
print(y4.shape)

# -------------------------------------------------------------------------------------------------------------------
# Exercise 5
X5, y5 = make_moons(n_samples=n_samples, noise=0.05, random_state=42)


#-------------------------------------------------------------------------
svm_boundary(X1, y1, kernel_type='rbf')
svm_boundary(X2, y2, kernel_type='rbf')
svm_boundary(X3, y3, kernel_type='rbf')
svm_boundary(X4, y4, kernel_type='rbf')
svm_boundary(X5, y5, kernel_type='rbf')
