<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Cron;

use Alpabit\ApiSkeleton\Cron\CronService;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Cron\CronBundle\Entity\CronJob;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CRONJOB", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Get extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(CronService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/cronjobs/{id}")
     *
     * @SWG\Tag(name="Cron Job")
     * @SWG\Response(
     *     response=200,
     *     description="Return cron job detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=CronJob::class, groups={"read"})
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        $this->logger->info(sprintf('[%s][%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id, serialize($request->query->all())));

        return $this->view($this->service->get($id));
    }
}
