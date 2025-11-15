import pandas as pd
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import StandardScaler

# Suponha que você exporte os dados do Moodle em CSV
data = pd.read_csv("student_data.csv")

# Features: engajamento, posts, entregas
X = data[['access_count', 'forum_posts', 'submissions']]
y = data['dropout']  # 1 = risco de evasão, 0 = seguro

scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

model = LogisticRegression()
model.fit(X_scaled, y)

data['risk_prob'] = model.predict_proba(X_scaled)[:, 1]

data.to_csv("student_risk_predictions.csv", index=False)
print("Predições geradas!")