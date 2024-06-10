#Task 3
from matplotlib import pyplot as plt
import numpy as np
import pandas as pd
import seaborn as sns
from sklearn.model_selection import train_test_split, cross_val_score, GridSearchCV
from sklearn.preprocessing import StandardScaler,MinMaxScaler,RobustScaler,Normalizer
from sklearn.neighbors import KNeighborsClassifier
from sklearn.metrics import classification_report
from sklearn.impute import SimpleImputer

# Data Acquisition

wine_df = pd.read_csv('winequality-red.csv')
# with pd.option_context('display.max_rows', 100, 'display.max_columns', 100):
#     print(wine_df.head(3))
##handling NAN values  
if wine_df.isnull().values.any():
    #print(wine_df.isnull().sum())
    wine_df.fillna(wine_df.median(),inplace=True)
else:
    print(wine_df.count())# count() counts all the non-NaN numbers
    print("No NaN values")
##this is to show that there is no NAN values
print("#------------------------")
# if wine_df.values.any():
#     print("if any features have null print them out\n")
#     print(wine_df.isnull().sum())


X = wine_df.drop('quality', axis=1).values
y = np.ravel(wine_df[['quality']])

#2) Data sampling
# Train-test split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)



      
#------------------------------------------------------------------------------------------

# 3)Exploratory Data Analysis
#we plot the data to visualize it
#------------------------------------------------------------------------------------------
#4) Data scaling( good for Distance and Gradient Descent based Problems)
# defining the scalers
StandardScaler_scaler = StandardScaler()
RobustScaler_scaler = RobustScaler()
MinMaxScale_scaler = MinMaxScaler()
Normalizer_scaler = Normalizer()

#fitting and transforming the scalers for the X_train values
StandardScaler_scaled_data= StandardScaler_scaler.fit_transform(X_train)
RobustScaler_scaled_data= RobustScaler_scaler .fit_transform(X_train)
MinMaxScaler_scaled_data= MinMaxScale_scaler.fit_transform(X_train)
Normalizer_scaled_data= Normalizer_scaler.fit_transform(X_train)



#------------------------------------------------------------------------------------------
#5) Feature selection and extraction 
wine_corr = wine_df.corr()
sorted_wine_corr=wine_corr['quality'].sort_values(key=abs,ascending=False)
print(sorted_wine_corr)


feature_names=['fixed acidity', 'volatile acidity', 'citric acid', 'residual sugar','chlorides', 'free sulfur dioxide', 'total sulfur dioxide', 'density','pH', 'sulphates', 'alcohol', 'quality']
#------------------------------------------------------------------------------------------
#6) Initialize model
knn = KNeighborsClassifier(n_neighbors=1)
#------------------------------------------------------------------------------------------
#7) Train model

#cross valication of 5 folds
Scaled_data=[StandardScaler_scaled_data,RobustScaler_scaled_data,MinMaxScaler_scaled_data,Normalizer_scaled_data]
Scaler=["StandardScaler","RobustScaler","MinMaxScaler","Normalizer"]

scorer=['accuracy','f1_weighted']
print("\n")

best_scaler_f1_macro=[]

for i,j in zip(Scaled_data,Scaler):
            cv_f1_macro=cross_val_score(knn,i,y_train,scoring= 'f1_macro',cv=5)
            print(f"this is the accuracy={cv_f1_macro} for the cross validation for {j} scaler\n")
            best_scaler_f1_macro.append(cv_f1_macro.mean())
            print("----------------------\n")

for i,j in zip(best_scaler_f1_macro,Scaler):
     print(f"{j} F1_macro ={i}\n")

print("")
#------------------------------------------------------------------------------------------
#8) Predict y-value
# Here we will use the best scaler and test using accuracy and weighted f1 scores

knn.fit(StandardScaler_scaled_data,y_train)

y_pred=knn.predict(StandardScaler_scaler.transform(X_test)) # we use the best scaler and we transform the X_test before we predict

print("\nClassification Report for test data:")
print(classification_report(y_test, y_pred))

#ask dane about the parameters 
""" # Scaling the data
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

# Hyperparameter tuning
param_grid = {'n_neighbors': np.arange(1, 21)}
knn = KNeighborsClassifier()
grid_search = GridSearchCV(knn, param_grid, cv=5, scoring='f1_macro')
grid_search.fit(X_train_scaled, y_train)

print("Best Parameters:", grid_search.best_params_)
print("Best Cross-validated Macro F1-score:", grid_search.best_score_)

# Evaluate on test data
best_knn = grid_search.best_estimator_
y_pred = best_knn.predict(X_test_scaled)

print("Classification Report (Test Data):")
print(classification_report(y_test, y_pred)) """
