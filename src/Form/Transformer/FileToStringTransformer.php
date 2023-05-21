<?php
namespace App\Form\Transformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;

class FileToStringTransformer implements DataTransformerInterface
{
    public function transform($value): ?string
    {
        if ($value instanceof File) {
            return $value->getPathname();
        }
        
        return null;
    }

    public function reverseTransform($value): ?File
    {
        if (!$value) {
            return null;
        }

        try {
            return new File($value);
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}