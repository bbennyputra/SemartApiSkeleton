<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Group;

use Alpabit\ApiSkeleton\Entity\Group;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\GroupType;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Service\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="GROUP", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Post extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    private $logger;

    public function __construct(FormFactory $formFactory, GroupService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Post("/groups")
     *
     * @SWG\Tag(name="Group")
     * @SWG\Parameter(
     *     name="group",
     *     in="body",
     *     type="object",
     *     description="Group form",
     *     @Model(type=GroupType::class)
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Crate new group",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Group::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(GroupType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var GroupInterface $group */
        $group = $form->getData();
        $this->service->save($group);

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $request->getContent()));

        return $this->view($this->service->get($group->getId()), Response::HTTP_CREATED);
    }
}
