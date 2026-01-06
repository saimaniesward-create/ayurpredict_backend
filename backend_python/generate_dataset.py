import pandas as pd
import random

def get_dosha_label(v, p, k):
    # Expert Logic for Ground Truth Labeling
    scores = {'Vata': v, 'Pitta': p, 'Kapha': k}
    sorted_scores = sorted(scores.items(), key=lambda item: item[1], reverse=True)
    
    primary_name = sorted_scores[0][0]
    primary_score = sorted_scores[0][1]
    secondary_name = sorted_scores[1][0]
    secondary_score = sorted_scores[1][1]
    
    # LOGIC 1: High Imbalance (> 60) - Priority Treatment
    if primary_score > 60:
        # Check for Dual (e.g., Vata 80, Pitta 78)
        if (primary_score - secondary_score) <= 5 and secondary_score > 60:
            return f"{primary_name}-{secondary_name}"
        return primary_name

    # LOGIC 2: Balanced Zone (31-60)
    # If all are in this range, the user is generally healthy. 
    # But usually we still pick the dominant trait for recommendations.
    
    # LOGIC 3: Low Imbalance (< 30)
    # If everything is low, it's a depletion state.
    
    # Minimal Gap Logic for Duals
    if (primary_score - secondary_score) <= 3:
        return f"{primary_name}-{secondary_name}"
    
    return primary_name

data = []
for _ in range(5000):
    # Generate random realistic scores (0-150) to handle uncapped totals
    v = random.randint(0, 150)
    p = random.randint(0, 150)
    k = random.randint(0, 150)
    
    label = get_dosha_label(v, p, k)
    
    data.append([v, p, k, label])

df = pd.DataFrame(data, columns=['Vata', 'Pitta', 'Kapha', 'Dosha_Type'])
df.to_csv('dosha_dataset.csv', index=False)

print(f"Generated 5,000 synthetic patient profiles. Saved to 'dosha_dataset.csv'.")
print("Sample:")
print(df.head())
