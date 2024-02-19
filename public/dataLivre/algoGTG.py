import random
import string

# Paramètres par défaut de l'algorithme génétique
default_population_size = 128
default_mutation_rate = 0.01562

def generate_random_word(length):
    return ''.join(random.choice('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') for _ in range(length))

def calculate_apte(word, target):
    return sum(1 for a, b in zip(word, target) if a == b)

def mutate_word(word, mutation_rate):
    mutated_word = list(word)
    for i in range(len(mutated_word)):
        if random.random() < mutation_rate:
            mutated_word[i] = random.choice('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    return ''.join(mutated_word)

def select_parents(population, target):
    apte_scores = [calculate_apte(word, target) for word in population]
    total_apte = sum(apte_scores)
    probabilities = [score / total_apte for score in apte_scores]
    
    if len(population) % 2 == 0:
        num_parents = 2
    else:
        num_parents = 3
        
    return random.choices(population, probabilities, k=num_parents)

def algorithme_genetique_basique(target, population_size, mutation_rate, max_iterations=None):
    target_length = len(target)

    population = [generate_random_word(target_length) for _ in range(population_size)]

    step = 0
    while max_iterations is None or step < max_iterations:
        step += 1
        parents = select_parents(population, target)
        enfant = mutate_word(parents[0], mutation_rate)
        
        apte_enfant = calculate_apte(enfant, target)
        apte_parent = max(calculate_apte(parent, target) for parent in parents)
        
        if apte_enfant >= apte_parent:
            population.remove(parents[1])
            population.append(enfant) #Remplacer le deuxième parent par l'enfant

            
        best_word = max(population, key=lambda word: calculate_apte(word, target))
        print(f"Étape {step}: Meilleur mot = '{best_word}' (Apte = {calculate_apte(best_word, target)})")
        
        if calculate_apte(best_word, target) == target_length:
            print(f"Mot cible trouvé par l'algorithme génétique basique à la génération {step}!")
            break

def algorithme_genetique_croiser(target, max_iterations):
    taille_population = input(f"Entrez la taille de la population (par défaut {default_population_size}): ")
    taux_mutation = input(f"Entrez le taux de mutation entre 0 et 1 (par défaut {default_mutation_rate}): ")

    if not taille_population:
        taille_population = default_population_size
    else:
        taille_population = int(taille_population)

    if not taux_mutation:
        taux_mutation = default_mutation_rate
    else:
        taux_mutation = float(taux_mutation)

    parents_a_reproduire = taille_population // 2 
    generations = 0
    trouve = False

    def creer_enfant(longueur):
        return ''.join(random.choice(string.ascii_letters) for _ in range(longueur))

    def evaluer_fitness(enfant):
        fitness = sum(1 for a, b in zip(enfant, target) if a == b)
        return fitness

    def selection_parents(population, n_parents):
        parents = sorted(population, key=lambda x: evaluer_fitness(x), reverse=True)
        return parents[:n_parents]

    def croiser(parent1, parent2):
        enfant1 = ""
        enfant2 = ""
    
        for i in range(len(parent1)):
            if i % 2 == 0:
                enfant1 += parent1[i]
                enfant2 += parent2[i]
            else:
                enfant1 += parent2[i]
                enfant2 += parent1[i]
    
        return enfant1, enfant2

    def muter(enfant):
        index_mutation = random.randint(0, len(enfant) - 1)
        nouveau_caractere = random.choice(string.ascii_letters)
        enfant_mute = enfant[:index_mutation] + nouveau_caractere + enfant[index_mutation+1:]
        return enfant_mute

    population = [creer_enfant(len(target)) for _ in range(taille_population)]

    while not trouve and (max_iterations is None or generations < max_iterations):
        parents = selection_parents(population, parents_a_reproduire) 
        nouveaux_enfants = []  # Initialisation de la liste des nouveaux enfants

        for i in range(0, parents_a_reproduire, 2):
            parent1 = parents[i]
            parent2 = parents[i+1]
            
            enfant1, enfant2 = croiser(parent1, parent2)
            
            if random.random() < taux_mutation:
                enfant1 = muter(enfant1)
            if random.random() < taux_mutation:
                enfant2 = muter(enfant2)
            
            nouveaux_enfants.extend([enfant1, enfant2])
        
        population = sorted(population, key=lambda x: evaluer_fitness(x), reverse=True)
        population[-len(nouveaux_enfants):] = nouveaux_enfants
        
        meilleur_enfant = population[0]
        generations += 1
        
        print(f"Génération {generations}: {meilleur_enfant} (apte= {evaluer_fitness(meilleur_enfant)})")
        
        if meilleur_enfant == target:
            trouve = True
            print(f"Mot trouvé par l'algorithme génétique croiser à la Génération {generations} !")
            break

def random_search(target, max_iterations=None):
    target_length = len(target)
    step = 0
    print("go")

    while max_iterations is None or step < max_iterations:
        step += 1
        random_word = generate_random_word(target_length)
        apte = calculate_apte(random_word, target)

#        print(f"Essai aléatoire {step}: Mot = '{random_word}' (Apte = {apte})")

        if apte == target_length:
            print(f"Essai aléatoire {step}: Mot = '{random_word}' (Apte = {apte})")
            print("Mot cible trouvé par essais aléatoires !")
            break

def main():
    custom_option = input("Choisissez la méthode pour définir le mot cible:\n1. Entrer manuellement le mot cible\n2. Générer automatiquement un mot cible en spécifiant la longueur\n")

    if custom_option == "1":
        target_word = input("Entrez le mot cible : ")
    elif custom_option == "2":
        custom_length = int(input("Entrez la longueur du mot cible : "))
        target_word = 'g' * custom_length  # Générer un mot avec N lettres 'g'
    else:
        print("Option non valide. Veuillez choisir 1 ou 2.")
        return
    
    max_iterations = input("Entrez le nombre maximum d'itérations (laissez vide pour aucune limite) : ")
    
    if max_iterations:
        max_iterations = int(max_iterations)
    else:
        max_iterations = None
    
    search_option = input("Choisissez une option de recherche:\n1. Algorithme génétique basique\n2. Algorithme génétique croiser\n3. Essais aléatoires\n")

    if search_option == "1":
        population_size = input(f"Entrez la taille de la population (par défaut {default_population_size}): ")
        mutation_rate = input(f"Entrez le taux de mutation (par défaut {default_mutation_rate}): ")

        if not population_size:
            population_size = default_population_size
        else:
            population_size = int(population_size)

        if not mutation_rate:
            mutation_rate = default_mutation_rate
        else:
            mutation_rate = float(mutation_rate)
        algorithme_genetique_basique(target_word, population_size, mutation_rate, max_iterations)

    elif search_option == "2":
        algorithme_genetique_croiser(target_word, max_iterations)

    elif search_option == "3":
        random_search(target_word, max_iterations)

if __name__ == "__main__":
    main()
