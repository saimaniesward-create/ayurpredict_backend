import sys
import json
import random
import os
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

# 1. Load Data
def load_intents(file_path):
    with open(file_path, 'r') as f:
        return json.load(f)

# 2. Train Model (On the fly)
def train_model(intents):
    corpus = []
    tags = []
    
    for intent in intents['intents']:
        for pattern in intent['patterns']:
            corpus.append(pattern)
            tags.append(intent['tag'])
            
    vectorizer = TfidfVectorizer(stop_words='english')
    X = vectorizer.fit_transform(corpus)
    
    return vectorizer, X, tags, corpus

# 3. Get Response
def get_response(user_input, vectorizer, X, tags, intents):
    # Vectorize user input
    user_vec = vectorizer.transform([user_input])
    
    # Calculate similarity
    similarities = cosine_similarity(user_vec, X)
    
    # Get best match
    best_match_idx = np.argmax(similarities)
    best_score = similarities[0][best_match_idx]
    
    # Threshold for "I don't understand"
    if best_score < 0.2:
        return "I am still learning Ayurveda. Can you please rephrase that?"
    
    best_tag = tags[best_match_idx]
    
    # Find response for this tag
    for intent in intents['intents']:
        if intent['tag'] == best_tag:
            return random.choice(intent['responses'])
            
    return "I am confused."

# --- Main Execution ---
if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"reply": "Error: No message provided."}))
        sys.exit(1)

    user_message = sys.argv[1]
    
    # Path to intents.json (Assumes it's in the same dir)
    current_dir = os.path.dirname(os.path.abspath(__file__))
    intents_path = os.path.join(current_dir, 'intents.json')
    
    try:
        data = load_intents(intents_path)
        vec, X_matrix, tag_list, _ = train_model(data)
        
        reply = get_response(user_message, vec, X_matrix, tag_list, data)
        
        print(json.dumps({"reply": reply}))
        
    except Exception as e:
        print(json.dumps({"reply": f"Error: {str(e)}"}))
