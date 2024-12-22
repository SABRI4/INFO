import re

# Définition des commandes valides dans le langage DRAW++
COMMAND_PATTERNS = {
   # Initialisation et affectation de variables (entiers et flottants)
    "variable_initialization": r"^[a-zA-Z_][a-zA-Z0-9_]*\s*<-\s*([a-zA-Z_][a-zA-Z0-9_]*|[-+]?\d+(\.\d+)?)(\s*[-+*/]\s*([a-zA-Z_][a-zA-Z0-9_]*|[-+]?\d+(\.\d+)?))*$",  # Pas de division ici pour éviter /0

    # Opérations avec division (contrôle de la division par zéro doit être séparée)
    "variable_initialization_with_division": r"^[a-zA-Z_][a-zA-Z0-9_]*\s*<-\s*([a-zA-Z_][a-zA-Z0-9_]*|[-+]?\d+(\.\d+)?)(\s*[-+*/]\s*([a-zA-Z_][a-zA-Z0-9_]*|[-+]?\d+(\.\d+)?))*$",

    # Initialisation de chaînes de caractères (concaténation uniquement avec '+')
    "variable_initialization_string": r"^[a-zA-Z_][a-zA-Z0-9_]*\s*<-\s*(\"[^\"]*\"|'[^']*')$",  # Pas d'opérations permises

    # Initialisation de booléens (opérations logiques autorisées)
    "variable_initialization_boolean": r"^[a-zA-Z_][a-zA-Z0-9_]*\s*<-\s*(true|false)(\s*(==|!=|&&|\|\|)\s*(true|false))*$",  

    # Entrées/sorties
    # Entrées/sorties
    "print": r"^print\((\".*\"|'.*')\)$",  # Ex: print("Hello World")
    "input": r"^[a-zA-Z_][a-zA-Z0-9_]*\s*=\s*input\(\".*\"\)$",  # Ex: name = input("Enter your name: ")

    # Commandes de dessin
    "draw_line": r"^draw_line\(\d+,\s*\d+,\s*\d+,\s*\d+\)$",  # Ex: draw_line(10, 20, 30, 40)
    "draw_circle": r"^draw_circle\(\d+,\s*\d+,\s*\d+\)$",  # Ex: draw_circle(50, 50, 10)
    "draw_rectangle": r"^draw_rectangle\(\d+,\s*\d+,\s*\d+,\s*\d+\)$",  # Ex: draw_rectangle(10, 10, 100, 50)

    # Gestion des couleurs et styles
    "set_color": r"^set_color\(\d+,\s*\d+,\s*\d+\)$",  # Ex: set_color(255, 0, 0)
    "set_line_width": r"^set_line_width\(\d+\)$",  # Ex: set_line_width(5)

    # Gestion de fenêtres
    "create_window": r"^window\(\d+,\s*\d+,\s*\".*\"\)$",  # Ex: window(800, 600, "Title")

    # Structures de contrôle
    "if_condition": r"^if\s*\(\s*([a-zA-Z_][a-zA-Z0-9_]*\s*(==|!=|<=|>=|<|>)\s*[-+]?\d+(\.\d+)?|\b(true|false)\b)\s*\)\s*\{[\s\S]*\}",  # Ex: if (x > 10) {
    "else_condition": r"^else\s*\{$",  # Ex: else {
    "else_if_condition": r"^else\s*if\s*\(\s*([a-zA-Z_][a-zA-Z0-9_]*\s*(==|!=|<=|>=|<|>)\s*[-+]?\d+(\.\d+)?|\b(true|false)\b)\s*\)\s*\{[\s\S]*\}",  # Ex: else if (x > 10) {
    "for_loop": r"^for\s*\(.*?;.*?;.*?\)\s*\{[\s\S]*\}",  # Ex: for (int i = 0; i < 10; i++) {
    "while_loop": r"^while\s*\(\s*([a-zA-Z_][a-zA-Z0-9_]*\s*(==|!=|<=|>=|<|>)\s*[-+]?\d+(\.\d+)?|\b(true|false)\b)\s*\)\s*\{[\s\S]*\}$",  # Ex: while (x < 10) {

    # Déclaration et appels de fonctions
    "function_declaration": r"^function\s+[a-zA-Z_][a-zA-Z0-9_]*\([\w,\s]*\):$",  # Ex: function my_function(param1, param2):
    "function_call": r"^[a-zA-Z_][a-zA-Z0-9_]*\([\w,\s]*\)$",  # Ex: my_function(10, "test")
}

RESERVED_KEYWORDS = {"print", "input", "true", "false", "draw_line", "draw_circle", "draw_rectangle", "set_color", "set_line_width", "window"}

def validate_additional_rules(lines):
    errors = []
    symbol_table = {}

    for i, line in enumerate(lines, start=1):
        # Vérification de l'initialisation des variables
        match_init = re.match(r"^([a-zA-Z_][a-zA-Z0-9_]*)\s*<-\s*", line)
        if match_init:
            variable_name = match_init.group(1)
            symbol_table[variable_name] = True

        # Vérification de l'utilisation des variables
        variables = re.findall(r"[a-zA-Z_][a-zA-Z0-9_]*", line)
        for variable in variables:
            if (
                variable not in symbol_table  # Non initialisée
                and variable not in RESERVED_KEYWORDS  # Pas un mot-clé réservé
                and not re.match(r"(true|false|\d+(\.\d+)?)", variable)  # Pas une constante ou un littéral
            ):
                errors.append(f"Ligne {i}: Variable '{variable}' utilisée sans être initialisée.")

        # Vérification de la division par zéro
        if re.search(r"/\s*0", line):
            errors.append(f"Ligne {i}: Division par zéro détectée.")

        # Vérification des types incompatibles dans les opérations
        if re.search(r"\".*\"\s*[-+*/%]\s*\".*\"", line):
            errors.append(f"Ligne {i}: Opérations non valides entre chaînes de caractères.")

        if re.search(r"(true|false)\s*[-+*/]\s*(true|false)", line):
            errors.append(f"Ligne {i}: Opérations arithmétiques non valides entre booléens.")

    return errors

def validate_syntax(draw_code):
    """
    Valide la syntaxe du code DRAW++.
    :param draw_code: Code DRAW++ sous forme de texte.
    :return: Liste des erreurs trouvées dans le code.
    """
    errors = []
    lines = draw_code.split("\n")

    for i, line in enumerate(lines, start=1):
        line = line.strip()
        if not line:  # Ignorer les lignes vides
            continue

        # Vérifier si la ligne correspond à un modèle
        if not any(re.match(pattern, line) for pattern in COMMAND_PATTERNS.values()):
            errors.append(f"Ligne {i}: Erreur de syntaxe.")

    additional_errors = validate_additional_rules(lines)
    errors.extend(additional_errors)
    return errors