<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Snapshot;
use AppBundle\Security\SnapshotVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SnapshotController extends Controller
{
    /**
     * @Route(path="/snapshot/{id}", name="fetch_snapshot_image")
     * @Method({"GET"})
     * @Security("has_role('ROLE_CUSTOMER')")
     *
     * @param Snapshot $snapshot
     * @return BinaryFileResponse
     */
    public function fetchSnapshotImageAction(Snapshot $snapshot)
    {
        try {
            $this->denyAccessUnlessGranted(SnapshotVoter::VIEW, $snapshot);
        } catch (\Exception $e) {
            //Throw NotFoundHttpException to prevent leaking data
            throw new NotFoundHttpException();
        }

        $filename = explode('/', $snapshot->getFilePath());
        $filename = end($filename);

        $response = new BinaryFileResponse($this->getParameter('snapshot_dir') . $snapshot->getFilePath());
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );

        return $response;
    }
}
