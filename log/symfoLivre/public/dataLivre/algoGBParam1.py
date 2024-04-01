import random
import string
import datetime
import os

# Paramètres de l'algorithme génétique
population_size = 1
mutation_rate = 0.0004
target_length = 2400  # Longueur du mot cible

def generate_random_word(length):
#    return 'a' * length
    return ''.join(random.choice('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') for _ in range(length))

def calculate_apte(word, target):
    return sum(1 for a, b in zip(word, target) if a == b)

def mutate_word(word, mutation_rate):
    mutated_word = list(word)
    for i in range(len(mutated_word)):
        if random.random() < mutation_rate:
            mutated_word[i] = random.choice('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    return ''.join(mutated_word)

def algorithme_genetique_basique(target, population_size, mutation_rate, max_iterations=None):
    target_length = len(target)

    population = [generate_random_word(target_length) for _ in range(population_size)]

    step = 0
    best_aptitude = 0
    cible_trouvee = False

    file_name = "resultatAlgoGenetiqueBasique"
    file_number = 1
    file_extension = ".txt"

    while os.path.exists(f"{file_name}{file_number}{file_extension}"):
        file_number += 1

    with open(f"{file_name}{file_number}{file_extension}", "a") as results_file:

        while max_iterations is None or step < max_iterations:
            step += 1
            parent = population[0]  # Utiliser le seul individu de la population
            enfant = mutate_word(parent, mutation_rate)

            apte_enfant = calculate_apte(enfant, target)
            apte_parent = calculate_apte(parent, target)

            if apte_enfant >= apte_parent:
                population[0] = enfant  # Mettre à jour le seul individu de la population

            best_word = population[0]

            if calculate_apte(best_word, target) > best_aptitude:
                best_aptitude = calculate_apte(best_word, target)
                now = datetime.datetime.now()
                result_str = f"{now.strftime('%Y-%m-%d %H:%M:%S')} - Étape {step}:' (Apte = {best_aptitude})"
                results_file.write(result_str + "\n")

            if best_aptitude == target_length:
                now = datetime.datetime.now()
                result_str = f"{now.strftime('%Y-%m-%d %H:%M:%S')} - Mot cible trouvé à l'étape {step}!"
                results_file.write(result_str + "\n")
                cible_trouvee = True
                break  # Quitter la boucle si la cible est trouvée

if __name__ == "__main__":
    algorithme_genetique_basique('g' * target_length, population_size, mutation_rate)
