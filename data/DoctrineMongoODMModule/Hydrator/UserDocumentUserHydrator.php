<?php

namespace DoctrineMongoODMModule\Hydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class UserDocumentUserHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['name'])) {
            $value = $data['name'];
            $return = (string) $value;
            $this->class->reflFields['name']->setValue($document, $return);
            $hydratedData['name'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['avatar'])) {
            $reference = $data['avatar'];
            if (isset($this->class->fieldMappings['avatar']['simple']) && $this->class->fieldMappings['avatar']['simple']) {
                $className = $this->class->fieldMappings['avatar']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['avatar'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['avatar']->setValue($document, $return);
            $hydratedData['avatar'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['avatarOriginal'])) {
            $reference = $data['avatarOriginal'];
            if (isset($this->class->fieldMappings['avatarOriginal']['simple']) && $this->class->fieldMappings['avatarOriginal']['simple']) {
                $className = $this->class->fieldMappings['avatarOriginal']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['avatarOriginal'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['avatarOriginal']->setValue($document, $return);
            $hydratedData['avatarOriginal'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['lastName'])) {
            $value = $data['lastName'];
            $return = (string) $value;
            $this->class->reflFields['lastName']->setValue($document, $return);
            $hydratedData['lastName'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['password'])) {
            $value = $data['password'];
            $return = (string) $value;
            $this->class->reflFields['password']->setValue($document, $return);
            $hydratedData['password'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['confirmPassword'])) {
            $value = $data['confirmPassword'];
            $return = (string) $value;
            $this->class->reflFields['confirmPassword']->setValue($document, $return);
            $hydratedData['confirmPassword'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['passwordEncripted'])) {
            $value = $data['passwordEncripted'];
            $return = (string) $value;
            $this->class->reflFields['passwordEncripted']->setValue($document, $return);
            $hydratedData['passwordEncripted'] = $return;
        }

        /** @Field(type="collection") */
        if (isset($data['roles'])) {
            $value = $data['roles'];
            $return = $value;
            $this->class->reflFields['roles']->setValue($document, $return);
            $hydratedData['roles'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['email'])) {
            $value = $data['email'];
            $return = (string) $value;
            $this->class->reflFields['email']->setValue($document, $return);
            $hydratedData['email'] = $return;
        }
        return $hydratedData;
    }
}