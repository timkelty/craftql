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

        $focalPoint = $this->createObjectType('AssetFocalPoint');
        $focalPoint->addFloatField('x');
        $focalPoint->addFloatField('y');
        $focalPoint->addFloatField('left');
        $focalPoint->addFloatField('top');
        $focalPoint->addFloatField('right');
        $focalPoint->addFloatField('bottom');
        $focalPoint->addStringField('coords');

        $this->addField('focalPoint')
            ->type($focalPoint)
            ->resolve(function ($root, $args) {
                $focalPoint = $root->getFocalPoint();

                if (!$focalPoint) {
                    return null;
                }

                $values = array_map('intval', array_merge($focalPoint, [
                    'left' => $focalPoint['x'] * $root->width - 1,
                    'top' => $focalPoint['y'] * $root->height - 1,
                    'right' => $focalPoint['x'] * $root->width + 1,
                    'bottom' => $focalPoint['y'] * $root->height+ 1,
                ]));

                $values['coords'] = implode('', [
                    $values['left'],
                    'x',
                    $values['top'],
                    ':',
                    $values['right'],
                    'x',
                    $values['bottom'],
                ]);

                return $values;
            });
    }

    function getResolveType() {
        return function ($type) {
            return ucfirst($type->volume->handle).'Volume';
        };
    }

}
