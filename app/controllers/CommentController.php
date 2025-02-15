<?php
require_once BASE_PATH . '/app/models/Comment.php';
class CommentController {

    public static function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu_commentaire = $_POST['message'];
            $mail_utilisateur = $_POST['userMail'];
            $id_proposition = $_POST['proposition_id'];

            $output = Comment::create($contenu_commentaire, $id_proposition, $mail_utilisateur);
            header('Location: ' . ROUTER_URL . 'server&group=' . $_POST['group_id'] . '&proposition=' . $_POST['proposition_id']);
        }
    }

    public static function display() {

        if (!isset($_GET['proposition'])) {
            return;
        }
        $id_proposition = (int)($_GET['proposition']);
        $comments = Comment::getByProposition($id_proposition);

        foreach ($comments as $comment) {
            $mail_utilisateur = $comment->__get('mail_utilisateur');
            echo "<div class='message'><span class='message-author'> $mail_utilisateur </span><span class='message-content'> {$comment->__get('contenu_commentaire')} </span></div>";
        }
    }

}