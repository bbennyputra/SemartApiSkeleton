<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class FormFactory
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function submitRequest(string $formType, Request $request, $data = null): FormInterface
    {
        $form = $this->formFactory->create($formType, $data);
        $form->submit($request->request->all());

        return $form;
    }
}
