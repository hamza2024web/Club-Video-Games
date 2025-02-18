--
-- Structure de la table `administrateur_principal`
--

CREATE TABLE `administrateur_principal` (
  `id` int NOT NULL,
  `nom_tournoi` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  `description` text,
  `type_evenement` enum('Tournament','Workshop','Exhibition') DEFAULT 'Tournament',
  `statut` enum('Planned','Open','In Progress','Completed','Cancelled') DEFAULT 'Planned',
  `organisateur_id` int DEFAULT NULL,
  `numbre_membre` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `genre_jeux`
--

CREATE TABLE `genre_jeux` (
  `id` int NOT NULL,
  `jeux_id` int DEFAULT NULL,
  `genre_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription_evenement`
--

CREATE TABLE `inscription_evenement` (
  `id` int NOT NULL,
  `date_inscription` date DEFAULT NULL,
  `membre_id` int DEFAULT NULL,
  `evenement_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription_jeu`
--

CREATE TABLE `inscription_jeu` (
  `id` int NOT NULL,
  `date_inscription` date DEFAULT NULL,
  `membre_id` int DEFAULT NULL,
  `jeu_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription_tournoi`
--

CREATE TABLE `inscription_tournoi` (
  `id` int NOT NULL,
  `date_inscription` date DEFAULT NULL,
  `membre_id` int DEFAULT NULL,
  `tournoi_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jeux`
--

CREATE TABLE `jeux` (
  `id` int NOT NULL,
  `nom_de_jeu` varchar(255) DEFAULT NULL,
  `description` text,
  `genre` varchar(255) DEFAULT NULL,
  `plateforme` varchar(255) DEFAULT NULL,
  `date_de_sortie` date DEFAULT NULL,
  `developpeur` varchar(255) DEFAULT NULL,
  `editeur` varchar(255) DEFAULT NULL,
  `image_de_jeu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id` int NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `organisateur`
--

CREATE TABLE `organisateur` (
  `id` int NOT NULL,
  `nom_tournoi` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tournoi`
--

CREATE TABLE `tournoi` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_de_debut` date DEFAULT NULL,
  `date_de_fin` date DEFAULT NULL,
  `numbre_membre` int DEFAULT NULL,
  `statut` enum('Pending','Open','In Progress','Paused','Completed','Cancelled','Full','Registration Closed') DEFAULT 'Pending',
  `regles` text,
  `organisateur_id` int DEFAULT NULL,
  `jeu_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('organisateur','membre') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur_principal`
--
ALTER TABLE `administrateur_principal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisateur_id` (`organisateur_id`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `genre_jeux`
--
ALTER TABLE `genre_jeux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jeux_id` (`jeux_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Index pour la table `inscription_evenement`
--
ALTER TABLE `inscription_evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `evenement_id` (`evenement_id`);

--
-- Index pour la table `inscription_jeu`
--
ALTER TABLE `inscription_jeu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `jeu_id` (`jeu_id`);

--
-- Index pour la table `inscription_tournoi`
--
ALTER TABLE `inscription_tournoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `tournoi_id` (`tournoi_id`);

--
-- Index pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `organisateur`
--
ALTER TABLE `organisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `tournoi`
--
ALTER TABLE `tournoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisateur_id` (`organisateur_id`),
  ADD KEY `fk_jeu` (`jeu_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur_principal`
--
ALTER TABLE `administrateur_principal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `genre_jeux`
--
ALTER TABLE `genre_jeux`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscription_evenement`
--
ALTER TABLE `inscription_evenement`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscription_jeu`
--
ALTER TABLE `inscription_jeu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscription_tournoi`
--
ALTER TABLE `inscription_tournoi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jeux`
--
ALTER TABLE `jeux`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `organisateur`
--
ALTER TABLE `organisateur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tournoi`
--
ALTER TABLE `tournoi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur_principal`
--
ALTER TABLE `administrateur_principal`
  ADD CONSTRAINT `administrateur_principal_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `evenement_ibfk_1` FOREIGN KEY (`organisateur_id`) REFERENCES `organisateur` (`id`);

--
-- Contraintes pour la table `genre_jeux`
--
ALTER TABLE `genre_jeux`
  ADD CONSTRAINT `genre_jeux_ibfk_1` FOREIGN KEY (`jeux_id`) REFERENCES `jeux` (`id`),
  ADD CONSTRAINT `genre_jeux_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`);

--
-- Contraintes pour la table `inscription_evenement`
--
ALTER TABLE `inscription_evenement`
  ADD CONSTRAINT `inscription_evenement_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`),
  ADD CONSTRAINT `inscription_evenement_ibfk_2` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `inscription_jeu`
--
ALTER TABLE `inscription_jeu`
  ADD CONSTRAINT `inscription_jeu_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`),
  ADD CONSTRAINT `inscription_jeu_ibfk_2` FOREIGN KEY (`jeu_id`) REFERENCES `jeux` (`id`);

--
-- Contraintes pour la table `inscription_tournoi`
--
ALTER TABLE `inscription_tournoi`
  ADD CONSTRAINT `inscription_tournoi_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`),
  ADD CONSTRAINT `inscription_tournoi_ibfk_2` FOREIGN KEY (`tournoi_id`) REFERENCES `tournoi` (`id`);

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `membre_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `organisateur`
--
ALTER TABLE `organisateur`
  ADD CONSTRAINT `organisateur_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `tournoi`
--
ALTER TABLE `tournoi`
  ADD CONSTRAINT `fk_jeu` FOREIGN KEY (`jeu_id`) REFERENCES `jeux` (`id`),
  ADD CONSTRAINT `tournoi_ibfk_1` FOREIGN KEY (`organisateur_id`) REFERENCES `organisateur` (`id`);
COMMIT;
