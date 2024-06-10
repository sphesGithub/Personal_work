import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
import seaborn as sns
from sklearn.metrics import classification_report
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler,MinMaxScaler,RobustScaler,Normalizer
from sklearn.model_selection import cross_val_score
from sklearn.neighbors import KNeighborsClassifier

#plt.style.use('seaborn-darkgrid')
#used this instead
#sns.set_style('darkgrid')

#1)Data Acquisition 
wine_df = pd.read_csv('winequality-red.csv')
with pd.option_context('display.max_rows', 100, 'display.max_columns', 100):
    print(wine_df.head(3))
##handling NAN values  
if wine_df.isnull().values.any():
    print(wine_df.isnull().sum())
    wine_df.fillna(wine_df.median(),inplace=True)
else:
    print(wine_df.count())# count() counts all the non-NaN numbers
    print("No NaN values")
##this is to show that there is no NAN values
print("#------------------------")
if wine_df.values.any():
    print(wine_df.isnull().sum())


X = wine_df.drop('quality', axis=1).values
y = np.ravel(wine_df[['quality']])
#------------------------------------------------------------------------------------------

#2) Data sampling

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)


#------------------------------------------------------------------------------------------

# 3)Exploratory Data Analysis
with pd.option_context('display.max_rows', None, 'display.max_columns', None):
    print(wine_df.describe())

sns.pairplot(wine_df, hue = 'quality', height = 3, palette="husl")
sns.violinplot(data=wine_df, x='quality', y='alcohol')
sns.FacetGrid(wine_df, hue='quality', height=6).map(plt.scatter, 'alcohol', 'fixed acidity').add_legend()
#plt.show()

# ### Distribution of wine quality (target variable)
plt.hist(wine_df['quality'], bins=6, edgecolor='black')
plt.xlabel('quality', fontsize=20)
plt.ylabel('count', fontsize=20)
plt.xticks(fontsize=15)
plt.yticks(fontsize=15)
#plt.show()

""" we need to find the best data exploration graphs to best display out data """
#------------------------------------------------------------------------------------------
#4) Data scaling( good for Distance and Gradient Descent based Problems)
# In order to
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
#no feature selection or extraction asked in this question
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

best_scaler_accuracy=[]
best_scaler_weighted=[]
for i,j in zip(Scaled_data,Scaler):
            cv_accuracy=cross_val_score(knn,i,y_train,scoring= 'accuracy',cv=5)
            print(f"this is the accuracy={cv_accuracy} for the cross validation for {j} scaler\n")
            best_scaler_accuracy.append(cv_accuracy.mean())
            cv_weighted=cross_val_score(knn,i,y_train,scoring= 'f1_weighted',cv=5)
            print(f"this is the weighted={cv_weighted} for the cross validation for {j} scaler\n")
            best_scaler_weighted.append(cv_weighted.mean())
            print("----------------------\n")

for i,j,z in zip(best_scaler_accuracy,best_scaler_weighted,Scaler):
     print(f"{z} accuracy= {i} and weighted= {j}\n")

print("The best scaler for this data is the MinMax")
#------------------------------------------------------------------------------------------
#8) Predict y-value
# Here we will use the best scaler and test using accuracy and weighted f1 scores

knn_model=knn.fit(MinMaxScaler_scaled_data,y_train)

y_pred=knn_model.predict(MinMaxScale_scaler.transform(X_test)) # we use the best scaler and we transform the X_test before we predict
#------------------------------------------------------------------------------------------
#9) Evaluate model
print("\nClassification Report for test data:")
print(classification_report(y_test, y_pred))