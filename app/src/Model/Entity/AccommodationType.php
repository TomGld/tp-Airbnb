<?php

namespace App\Model\Entity;

use App\Model\Repository\CategoryRepository;
use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class AccommodationType extends Entity
{
    //functions avec ses getter and setter :

    protected string $label;

        /**
     * Get the value of label
     */ 
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @return  self
     */ 
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }





































//ANCIENS

    // Liaisons
    protected array $categories;
    public function getCategories(): array
    {
        if( ! isset( $this->categories ) ) {
            $this->categories = RepoManager::getRM()->getCategoryRepo()->getAllForCar( $this->id );
        }

        return $this->categories;
    }
    public function addCategories( array $ids_categories ): self
    {
        $cat_repo = RepoManager::getRM()->getCategoryRepo();

        // 1 - On détache toutes les catégories existante sur la voiture
        $cat_repo->detachAllForCar( $this->id );

        if( empty( $ids_categories ) ) {
            return $this;
        }

        // 2 - On réaffecte seulement les catégories demandées
        $cat_repo->attachForCar( $ids_categories, $this->id );

        return $this;
    }

}