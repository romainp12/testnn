<?php

namespace App\Service;

use App\Entity\SupportHasMedia;
use App\Entity\Theme;
use App\Entity\Event;
use App\Entity\User;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class DirectoryNamer implements DirectoryNamerInterface
{
    public function directoryName($object, PropertyMapping $mapping): string
    {
        $dir = "";

        switch ($object) {
            case $object instanceof Event :
                $dir = "event";
                break;
            case $object instanceof User :
                $dir = "user";
                break;
            case $object instanceof Theme :
                $dir = "theme";
                break;
            case $object instanceof SupportHasMedia :
                $dir = "support-media";
                break;
        }

        return $dir;
    }
}
