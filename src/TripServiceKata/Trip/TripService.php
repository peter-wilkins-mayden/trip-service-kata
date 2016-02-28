<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;

class TripService
{
    public function getTripsByUser(User $user) {
        $tripList = array();
        $loggedUser = $this->getLoggedInUser();
        $isFriend = false;
        if ($loggedUser != null) {
            foreach ($user->getFriends() as $friend) {
                if ($friend == $loggedUser) {
                    $isFriend = true;
                    break;
                }
            }
            if ($isFriend) {
                $tripList = $this->findTripsByUser($user);
            }
            return $tripList;
        } else {
            throw new UserNotLoggedInException();
        }
    }

    /**
     * Proxy method to be overridden in test class to avoid calling static method
     * @throws \TripServiceKata\Exception\DependentClassCalledDuringUnitTestException
     */
    protected function getLoggedInUser()
    {
        return UserSession::getInstance()->getLoggedUser();
    }

    /**
     * Proxy method to be overridden in test class to avoid calling static method
     * @param User $user
     * @throws \TripServiceKata\Exception\DependentClassCalledDuringUnitTestException
     */
    protected function findTripsByUser(User $user)
    {
        return TripDAO::findTripsByUser($user);
    }
}
