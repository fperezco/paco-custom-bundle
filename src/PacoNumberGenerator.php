<?php

namespace Paco\CustomPacoBundle;

use Paco\CustomPacoBundle\Interfaces\MeetingMessageProviderInterface;



class PacoNumberGenerator
{
    private $baseNumber;
    private $topNumber;
    /**
     * @var MeetingMessageProviderInterface[]
     */
    private $meetingMessageProviders;

    //the idea is calculate the greeting list just 1 time
    //using all the services tagged to produce words for the  bundle
    private $greetingList;

    public function __construct(iterable $meetingMessageProviders, $baseNumber = 0, $topNumber = 100)
    {
        $this->baseNumber = $baseNumber;
        $this->topNumber = $topNumber;
        $this->meetingMessageProviders = $meetingMessageProviders;
    }

    public function getNumber()
    {
        //$randomMeetingIndex = array_rand($this->meetingMessageProviders->getWordList(), 1);
        //now with the tags we have to use the caculated array
        $this->getWordList();
        $randomMeetingIndex = array_rand($this->greetingList, 1);
        $randomMeetingWord = $this->greetingList[$randomMeetingIndex];

        $randomNumber = random_int($this->baseNumber, $this->topNumber);
        return $randomMeetingWord." ". $randomNumber;
    }

    // old method for a single service
    /*
    private function getWordList(): array
    {
        return $this->meetingMessageProvider->getWordList();
    }
    */

    private function getWordList(): array
    {
        if (null === $this->greetingList) {
            $words = [];
            foreach ($this->meetingMessageProviders as $meetingProvider) {
                $words = array_merge($words, $meetingProvider->getWordList());
            }
            if (count($words) <= 1) {
                throw new \Exception('Word list must contain at least 2 words, yo!');
            }
            $this->greetingList = $words;
        }
        return $this->greetingList;
    }
}