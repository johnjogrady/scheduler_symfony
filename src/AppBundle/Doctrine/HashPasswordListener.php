<?php
namespace AppBundle\Doctrine;

/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 01/04/2017
 * Time: 08:47
 */
use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class HashPasswordListener implements EventSubscriber
{
    /**
     * @var \AppBundle\\UserPasswordEncoder
     */
    private $passwordEncoder;


    /**
     * HashPasswordListener constructor.
     */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);


    }

    /**
     * @param User $entity
     */
    public function encodePassword(User $entity)
    {
        $encoded = $this->passwordEncoder->encodePassword($entity, $entity);
        $entity->setPassword($encoded);
    }
}