<?php

/**
il faut que tu crées une bibliothèque qui te permette d'afficher Titre, Auteur, Genre, Résumé, Note de 1 à 10. Tu dois pouvoir afficher la liste complète soit dans l'ordre de saisie (ca c'est le 1er état), soit en ordre alphabétique, soit par note. (attention, ne pas trier le tabelau directement ;) ) Ensuite, il faut un module pour ajouter un livre, plus voir en détails la fiche du livre et bien sûr pouvoir supprimer un livre. j'ai un formulaire pour l'ajout
pour tout le reste, c'est géré par mes classes et les méthodes que j'appelle en règle générale sur des liens
2 classes, 1 index, 1 page ajouter ^^ (je n'ai aps fait la page Fiche encore)
*/



class Livre
{
    private $_id;
    private $_titre;
    private $_auteur;
    private $_genre;
    private $_resume;
    private $_note;


// construct : permet d'hydrater directement l'objet lors de l'instanciation de la classe

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

//hydrate : permet d'assigner aux attributs de l'objet les valeurs correspondantes, passées en paramètre dans un tableau.

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set' . ucfirst($key);
            // Si le setter correspondant existe, on l'appelle.
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }


// getters et setters

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $id = (int) $id;
        if ($id > 0) {
            $this->_id = $id;
        }
    }

    public function getTitre()
    {
        return $this->_titre;
    }

    public function setTitre($titre)
    {
        if (is_string($titre)) {
            $this->_titre = $titre;
        }
    }

    public function getAuteur()
    {
        return $this->_auteur;
    }

    public function setAuteur($auteur)
    {
        if (is_string($auteur)) {
            $this->_auteur = $auteur;
        }
    }

    public function getGenre()
    {
        return $this->_genre;
    }

    public function setGenre($genre)
    {
        if (is_string($genre)) {
            $this->_genre = $genre;
        }
    }

    public function getResume()
    {
        return $this->_resume;
    }

    public function setResume($resume)
    {
        if (is_string($resume)) {
            $this->_resume = $resume;
        }
    }

    public function getNote()
    {
        return $this->_note;
    }

    public function setNote($note)
    {
        $note = (int) $note;
        if (($note >= 0) && ($note <= 10)) {
            $this->_note = $note;
        }
        else {
            echo 'Ecrire une note entre 0 et 10';
        }
    }

    public function titreValide()
    {
        return !empty($this->_titre);
    }
}
