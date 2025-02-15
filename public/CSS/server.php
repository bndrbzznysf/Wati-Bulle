<?php header("Content-type: text/css"); ?>

.container {
    display: flex;
    width: 100vw;
    min-height: calc(100vh - var(--navbar-height));
}

/* Sidebar */
.sidebar {
    position: fixed;
    height: 100vh;
    width: 240px;
    background-color: var(--gris-fonce);
    padding: 20px;
    overflow-y: auto; /* Allow scrolling if content overflows */
    overflow-x: hidden;
}

.sidebar.left {
    left: 0;
    border-right: 1px solid <?php echo $couleur_group; ?>;
}

.sidebar.right {
    right: 0;
    border-left: 1px solid <?php echo $couleur_group; ?>;
}

.sidebar h2 {
    font-size: 20px;
    margin-bottom: 20px;
}

.sidebar .button {
    margin-inline: auto;
}

.group-list {
    list-style: none;
    padding: 0;
}

.group-item {
    padding: 0;
    margin-bottom: 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.group-item.active {
    background-color: <?php echo $couleur_group; ?>;
    color: var(--texte-clair);
}

.group-item:hover {
    background-color: <?php echo $couleur_group; ?>77;
}

.group-item a {
    color: var(--texte-clair);
    text-decoration: none;
    margin: 0;
    width: 100%;
    height: 100%;
    display: block;
    padding: 10px;
}

/* Main content */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 20px;
    padding-inline: 240px; /* Sidebar width */
}

/* Section des propositions */
.proposal-section {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    max-width: calc(100% - 64px);
    width: 840px;
    margin-inline: 32px;
    padding: 32px;
    border: 1px solid <?php echo $couleur_group; ?>;
    border-radius: 32px;
    box-shadow: 0 0 12px var(--vert-primaire);
}

/* Section du chat */
.chat-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    margin-top: 20px;
    padding: 32px;
}

.chat-window {
    background-color: var(--gris-fonce);
    padding: 20px;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    height: 400px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    margin-bottom: 20px;
}

.message {
    margin-bottom: 10px;
}

.message-author {
    font-weight: bold;
    color: var(--vert-primaire);
}

.message-content {
    color: var(--texte-clair);
}

.chat-input {
    display: flex;
    gap: 10px;
}

.chat-input textarea {
    flex: 1;
    padding: 10px;
    border: 1px solid var(--gris-clair);
    border-radius: 8px;
    background-color: var(--fond-sombre);
    color: var(--texte-clair);
}

.chat-input button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background-color: var(--vert-primaire);
    color: var(--texte-clair);
    cursor: pointer;
}

.chat-input button:hover {
    background-color: var(--vert-secondaire);
}