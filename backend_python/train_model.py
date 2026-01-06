import pandas as pd
import joblib
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score

def train():
    # 1. Load Data
    try:
        df = pd.read_csv('dosha_dataset.csv')
    except FileNotFoundError:
        print("Error: 'dosha_dataset.csv' not found. Run generate_dataset.py first.")
        return

    X = df[['Vata', 'Pitta', 'Kapha']]
    y = df['Dosha_Type']

    # 2. Split
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # 3. Train
    print("Training Random Forest Classifier...")
    model = RandomForestClassifier(n_estimators=100, random_state=42)
    model.fit(X_train, y_train)

    # 4. Evaluate
    y_pred = model.predict(X_test)
    acc = accuracy_score(y_test, y_pred)
    print(f"Model Accuracy: {acc * 100:.2f}%")

    # 5. Save
    joblib.dump(model, 'dosha_model.pkl')
    print("Model saved to 'dosha_model.pkl'")

if __name__ == "__main__":
    train()
