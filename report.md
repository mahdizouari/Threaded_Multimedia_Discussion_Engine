# 📄 Rapport Complet : Architecture & Fonctionnement du Forum Pulse

Ce document détaille l'organisation structurelle, le design système et les mécanismes complexes qui font de **Pulse Multimedia Forum** une application robuste, moderne et conforme aux standards ergonomiques (**ISO 9241-12**).

---

## 🏗️ 1. Architecture du Front-End (Views)

Le répertoire `resources/views` est désormais organisé par **Rôle**, ce qui rend la gestion des permissions plus intuitive pour les développeurs.

### 📁 Organisation par Dossiers
- **`admin/`** : Contient le **Team Hub** (pour gérer l'équipe) et le dossier **Categories** (pour structurer les communautés).
- **`moderator/`** : Regroupe les outils de surveillance (Tableau de bord, Approbations de posts, et Signalements).
- **`user/`** : Le cœur de l'application. Contient l'accueil (`welcome`), les posts, la messagerie et le profil.
- **`auth/`** : Gère l'inscription, la connexion et la sécurité (système).
- **`layouts/`** : Contient le squelette principal de l'application.

### 🍱 Le Layout Central (`layouts/pulse.blade.php`)
C'est le fichier le plus important. Il définit la structure visuelle globale :
- **Sidebar (Gauche)** : Navigation persistante et accès rapide aux catégories.
- **Main Feed (Centre)** : Là où le contenu dynamique s'affiche.
- **Widget Sidebar (Droite)** : Affiche les informations de l'utilisateur connecté et les widgets contextuels.
- **Système de Modales** : Centralise les formulaires (Création de post, Signalement) pour une expérience fluide sans rechargement de page.

---

## ⚙️ 2. Fonctions Complexes & Logique "Métier"

Certaines fonctionnalités ont nécessité une logique avancée pour garantir une expérience "premium" :

### 🎢 Le Carrousel "Trending" (Auto-Slide)
- **Le Défi** : Afficher 6 posts, n'en montrer que 3 à la fois, et les faire défiler horizontalement.
- **La Solution** : Nous utilisons un **Viewport** (fenêtre visible) et un **Track** (rail qui contient les cartes). Un script JavaScript calcule la largeur des cartes et déplace le rail toutes les 5 secondes avec une boucle infinie (`index % totalItems`).

### 🛡️ Algorithme d'Auto-Modération
- **Le Défi** : Protéger la communauté sans intervention humaine constante.
- **La Solution** : Chaque utilisateur possède un compteur `violations_count`. 
  - Si un modérateur fait une action de "Rejet", le compteur augmente de +1.
  - À **5 points**, le système change l'état de l'utilisateur en `is_blocked = true` instantanément.
  - L'administrateur peut réinitialiser ce compteur depuis le **Team Hub**.

### 🔍 Flux Personnalisé "For You" (Algorithme d'intérêts)
- **La Solution** : Lors de la récupération des données en SQL, nous utilisons une condition `ORDER BY category_id IN (...) DESC`. Cela permet de placer les posts des catégories suivies par l'utilisateur tout en haut de sa page d'accueil, créant un sentiment de personnalisation forte.

### 🎭 Génération Dynamique d'Avatars
- **La Solution** : Utilisation de l'API **DiceBear**. En passant le nom de l'utilisateur comme "graine" (seed), l'API renvoie un avatar unique. Si l'utilisateur change de nom, son visage change aussi !

---

## 🚀 3. Fonctionnement Global du Projet

Le cycle de vie sur Pulse Forum se déroule comme suit :

1.  **Inscription & Personnalisation** : L'utilisateur s'inscrit, choisit un pseudo (possibilité de le générer en un clic) et sélectionne ses thématiques préférées.
2.  **Interaction sociale** : L'utilisateur publie des contenus multimédias (images/texte). Ses posts apparaissent dans le flux global et dans le carrousel s'ils génèrent de l'engagement.
3.  **Système de Feedback** : La communauté réagit via le système "Top / Flop". Un post avec beaucoup de "Top" monte dans le classement.
4.  **Assurance Qualité (Modération)** :
    - Les posts des nouveaux utilisateurs passent par une file d'attente (**Approvals**).
    - Les membres peuvent signaler des contenus inappropriés (**Reports**).
    - Les modérateurs nettoient le flux, ce qui déclenche automatiquement le système d'infractions cité plus haut.

---

> [!IMPORTANT]
> Cette structure et ce fonctionnement respectent les principes de **Visibilité de l'état du système** et de **Prévention des erreurs**, deux piliers majeurs de l'IHM moderne.

**Rapport technique version 2.0 — Pulse Multimedia Forum**
