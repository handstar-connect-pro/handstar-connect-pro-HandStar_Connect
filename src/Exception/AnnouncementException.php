<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Classe de base pour les exceptions métier liées aux annonces.
 */
abstract class AnnouncementException extends \RuntimeException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'une annonce n'est pas active.
 */
class AnnouncementNotActiveException extends AnnouncementException
{
    public function __construct(string $message = 'Cette annonce n\'est plus active.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'une annonce est expirée.
 */
class AnnouncementExpiredException extends AnnouncementException
{
    public function __construct(string $message = 'Cette annonce est expirée.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'un utilisateur ne peut pas répondre à une annonce.
 */
class CannotRespondToAnnouncementException extends AnnouncementException
{
    public function __construct(string $message = 'Vous ne pouvez pas répondre à cette annonce.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'un utilisateur a déjà répondu à une annonce.
 */
class AlreadyRespondedException extends AnnouncementException
{
    public function __construct(string $message = 'Vous avez déjà répondu à cette annonce.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'une annonce est déjà dans les favoris.
 */
class AlreadyInFavoritesException extends AnnouncementException
{
    public function __construct(string $message = 'Cette annonce est déjà dans vos favoris.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'une annonce n'est pas dans les favoris.
 */
class NotInFavoritesException extends AnnouncementException
{
    public function __construct(string $message = 'Cette annonce n\'est pas dans vos favoris.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée lorsqu'une annonce est fermée.
 */
class AnnouncementClosedException extends AnnouncementException
{
    public function __construct(string $message = 'Cette annonce est fermée.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception levée pour les erreurs de validation d'annonce.
 */
class AnnouncementValidationException extends AnnouncementException
{
    public function __construct(string $message = 'Validation de l\'annonce échouée.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
