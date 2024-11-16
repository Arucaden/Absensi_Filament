from scipy.spatial.distance import cosine
import json
import sys

def match_faces(input_vector_json, stored_vectors_json):
    threshold = 0.999999  # Set threshold for similarity

    try:
        # Parse input JSON
        input_vector = json.loads(input_vector_json)
        stored_vectors = json.loads(stored_vectors_json)
    except json.JSONDecodeError as e:
        print(json.dumps({'error': f'Invalid JSON format: {str(e)}'}))
        sys.exit(1)

    # Validate input vector
    if not isinstance(input_vector, list) or not all(isinstance(x, (int, float)) for x in input_vector):
        print(json.dumps({'error': 'Invalid input vector format'}))
        sys.exit(1)

    # Validate stored vectors
    if not isinstance(stored_vectors, list) or not all(isinstance(vec, list) for vec in stored_vectors):
        print(json.dumps({'error': 'Invalid stored vectors format'}))
        sys.exit(1)

    # Compare input vector with each stored vector
    for stored_vector in stored_vectors:
        if len(input_vector) != len(stored_vector):
            print(json.dumps({'error': 'Input vector and stored vector sizes do not match'}))
            sys.exit(1)

        # Calculate cosine similarity
        similarity = 1 - cosine(input_vector, stored_vector)
        if similarity >= threshold:
            return True

    return False

if __name__ == "__main__":
    # Check if the correct number of arguments is provided
    if len(sys.argv) != 3:
        print(json.dumps({'error': 'Usage: python face_matching.py <input_vector_json> <stored_vectors_json>'}))
        sys.exit(1)

    input_vector_json = sys.argv[1]
    stored_vectors_json = sys.argv[2]

    # Call the match_faces function
    result = match_faces(input_vector_json, stored_vectors_json)
    print(json.dumps({'match': result}))
