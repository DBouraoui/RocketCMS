<?php

namespace App\Security\Voter;

use App\Entity\MenuLink;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class MenuLinkVoter extends Voter
{
    public const VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW])
            && $subject instanceof MenuLink;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        if (!$subject instanceof MenuLink){
            return false;
        }

        switch ($attribute) {

            case self::VIEW:
                  return  $subject->isActive();
                break;
        }
        return false;
    }
}
