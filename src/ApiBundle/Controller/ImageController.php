<?php

namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Service\FileUploadService;
use ApiBundle\Form\Type\FileType;
use AccessibilityBarriersBundle\Entity\Image;
use ApiBundle\Model\File;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class ImageController
 */
class ImageController extends BaseController
{
    const IMAGE_TYPE = 'jpg';

    /**
     * @ApiDoc(
     *  description="Upload new image",
     *  parameters={
     *      {"name"="file", "dataType"="string", "required"=true, "description"="base64"}
     *  })
     * )
     * @param Request $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {

        $form = $this->createForm(FileType::class);

        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        /** @var File $file */
        $file = $form->getData();

        $originalFileName = sprintf('%s.%s', Uuid::uuid4()->toString(), self::IMAGE_TYPE);
        $thumbnailFileName = sprintf('%s.%s', Uuid::uuid4()->toString(), self::IMAGE_TYPE);

        /** @var FileUploadService $fileUploadService */
        $fileUploadService = $this->get('accessibility_barriers.services.file_upload_service');

        $imageFile = $fileUploadService->base64Decode($file->data);
        $fileUploadService->upload($originalFileName, $imageFile);
        $fileUploadService->generateThumb($originalFileName, $thumbnailFileName);

        $image = new Image();
        $image->setOriginal($originalFileName);
        $image->setThumbnail($thumbnailFileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        return $this->success($image, 'Image', Response::HTTP_CREATED, array('IMAGE_BASIC'));
    }

    /**
     * @ApiDoc(
     *  description="Delete image"
     * )
     * @param Image $image
     * @return Response
     * @ParamConverter("image", class="AccessibilityBarriersBundle\Entity\Image", options={"id" = "id"})
     */
    public function deleteAction(Image $image)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        return $this->success(array('status' => 'Removed', 'message' => 'Image properly removed'), 'Image');
    }
}
