<?php

class LivresManager
{
    private $_db; // instance de PDO

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function add(Livre $livre)
    {
        $q = $this->_db->prepare('INSERT INTO livres(titre, auteur, genre, resume, note) VALUES(:titre, :auteur, :genre, :resume, :note)'); // preparation de la requete d'insertion

        // Assignatin des valeurs
        $q->bindValue(':titre', $livre->getTitre());
        $q->bindValue(':auteur', $livre->getAuteur());
        $q->bindValue(':genre', $livre->getGenre());
        $q->bindValue(':resume', $livre->getResume());
        $q->bindValue(':note', $livre->getNote());

        $q->execute(); // Execution de la requete

        // Hydratation du livre passé en paramètre avec assignation
        $livre->hydrate([
            'id' => $this->_db->lastInsertId()
        ]);
    }

    public function count() // Execute une requête COUNT() et retourne le nombre de résultats retournés
    {
        return $this->_db > query('SELECT COUNT(*) FROM livres')->fetchColumn();
    }

    public function delete(Livre $livre)
    {
        $this->_db->exec('DELETE FROM livres WHERE id = ' . $livre->getId());
    }

    public function exists($info)
    {
        if (is_int($info)) // On veut voir si tel livre ayant pour id $info existe.
        {
            return (bool)$this->_db->query('SELECT COUNT (*) FROM livres WHERE id = ' . $info)->fetchColumn();
        }

        // sinon c'est qu'on veut vérifier que le titre existe ou pas.

        $q = $this->_db->prepare('SELECT COUNT(*) FROM livres WHERE titre = :titre');
        $q->execute([':titre' => $info]);

        return (bool)$q->fetchColumn();
    }


    public function get($info) // execute une requete et retourne un objet Livre
    {
        if (is_int($info)) {
            $q = $this->_db->query('SELECT id, titre, auteur, genre, resume, note FROM livres WHERE id = ' . $info);
            $donnees = $q->fetch(PDO::FETCH_ASSOC);

            return new Livre($donnees);
        } else {
            $q = $this->_db->prepare('SELECT id, titre, auteur, genre, resume, note FROM livres WHERE titre = :titre');
            $q->execute([':titre' => $info, ':auteur' => $info, ':genre' => $info, ':resume' => $info, ':note' => $info]);

            return new Livre($q->fetch(PDO::FETCH_ASSOC));
        }
    }

    public function getList($titre) // Retourne la liste de tous les livres dont le titre n'est pas $titre. Le réusltat sera un tableau d'instances
    {
        $articles = [];

        $q = $this->_db->prepare('SELECT id, titre, auteur, genre, resume, note FROM livres WHERE titre <> :titre ORDER BY titre');
        $q->execute([':titre' => $titre]);

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = new Livre($donnees);
        }

        return $articles;
    }


    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }

}