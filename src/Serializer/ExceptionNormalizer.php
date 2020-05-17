<?php
declare(strict_types=1);

namespace App\Serializer;

use Exception;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

/**
 * @codeCoverageIgnore
 */
class ExceptionNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     * @param Exception $object
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $data = [
            'exception' => get_class($object),
            'message'   => $object->getMessage(),
            'code'      => $object->getCode(),
            'file'      => $object->getFile(),
            'line'      => $object->getLine(),
            'trace'     => $object->getTrace(),
        ];

        while ($object = $object->getPrevious()) {
            $data['previous'] = $this->normalize($object);
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Throwable;
    }
}
