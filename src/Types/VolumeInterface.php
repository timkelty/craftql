<?php

namespace markhuot\CraftQL\Types;

use markhuot\CraftQL\Builders\InterfaceBuilder;
use markhuot\CraftQL\FieldBehaviors\AssetTransformArguments;

class VolumeInterface extends InterfaceBuilder {

    function boot() {
        $this->addIntField('id');
        $this->addStringField('url')
            ->use(new AssetTransformArguments);

        $this->addStringField('width')
            ->use(new AssetTransformArguments);

        $this->addStringField('height')
            ->use(new AssetTransformArguments);

        $this->addIntField('size');
        $this->addField('folder')->type(VolumeFolder::class);
        $this->addStringField('mimeType');
        $this->addStringField('title');
        $this->addStringField('extension');
        $this->addStringField('filename');
        $this->addDateField('dateCreatedTimestamp');
        $this->addDateField('dateCreated');
        $this->addDateField('dateUpdatedTimestamp');
        $this->addDateField('dateUpdated');

        $this->addStringField('path')
            ->resolve(function ($root, $args) {
                if (!$root->volume->subfolder) {
                    return $root->path;
                }

                return rtrim($root->volume->subfolder, '/') . '/' . $root->path;
            });

        $focalPoint = $this->createObjectType('AssetFocalPoint');
        $focalPoint->addFloatField('x');
        $focalPoint->addFloatField('y');

        $this->addField('focalPoint')
            ->type($focalPoint)
            ->resolve(function ($root, $args) {
                return $root->getFocalPoint();
            });
    }

    function getResolveType() {
        return function ($type) {
            return ucfirst($type->volume->handle).'Volume';
        };
    }

}
