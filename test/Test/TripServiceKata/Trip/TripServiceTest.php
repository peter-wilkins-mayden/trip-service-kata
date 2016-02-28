<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit_Framework_TestCase;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;
use \TripServiceKata\Trip\Trip;


class TripServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TripService
     */
    private $tripService;
    private $loggedInUser;


    protected function setUp()
    {


    }

    /** @test
     * @expectedException TripServiceKata\Exception\UserNotLoggedInException */
    public function
    should_throw_exception_when_user_not_logged_in() {
        $this->tripService = new GuestTripService();
        $this->tripService->getTripsByUser(new User('bob'));

    }
    /** @test */
    public function should_not_return_any_trips_when_users_are_not_friends(){
        $this->tripService = new TestableTripService();
        $friendTrips = $this->tripService->getTripsByUser(new User('Bob'));
        $this->assertEquals(0, count($friendTrips));
    }
    /** @test */
    public function should_return_trip_when_users_are_friends(){
        $this->tripService = new FriendTripService();
        $bob = new User('Bob');
        $alice = new User('Alice');
        $alice->addTrip(new Trip('Mayden'));
        $bob->addFriend($alice);
        $friendTrips = $this->tripService->getTripsByUser($bob);
        $this->assertEquals(1, count($friendTrips));
    }
}

class TestableTripService extends TripService
{
    protected function getLoggedInUser(){
        return true;
    }
    public function findTripsByUser(User $user){
        return [];
    }

}
class GuestTripService extends TripService
{

    protected function getLoggedInUser()
    {
        return null;
    }
}
class FriendTripService extends TripService
{
    protected function getLoggedInUser(){
        return true;
    }
    public function findTripsByUser(User $user){
        return ['mayden'];
    }

}