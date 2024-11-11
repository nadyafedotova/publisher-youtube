<?php

namespace App\Tests\Security\Voter;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Security\Voter\AuthorBookVoter;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorBookVoterTest extends AbstractTestCase
{
    private BookRepository $bookRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
    }

    final public function testVoteNotSupports(): void
    {
        $user = new User();
        $voter = new AuthorBookVoter($this->bookRepository);
        $token = $this->createToken($user);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, 1, ['test']));
    }

    final public function testVote(): void
    {
        $this->vote(true, VoterInterface::ACCESS_GRANTED);
    }

    final public function testVoteRestrict(): void
    {
        $this->vote(false, VoterInterface::ACCESS_DENIED);
    }

    final public function vote(bool $existsUserBookIdRequest, int $expectedAccess): void
    {
        $user = new User();
        $voter = new AuthorBookVoter($this->bookRepository);
        $token = $this->createToken($user);

        $this->bookRepository->expects($this->once())
            ->method('existsUserBookI')
            ->with($existsUserBookIdRequest);

        $this->assertEquals($expectedAccess, $voter->vote($token, 1, [AuthorBookVoter::IS_AUTHOR]));
    }

    private function createToken(User $user): TokenInterface
    {
        return new readonly class($user) implements TokenInterface {
            public function __construct(
                private User $user
            )
            {
            }

            public function __toString(): string
            {
                return '';
            }

            public function getUserIdentifier(): string
            {
                return $this->user->getUserIdentifier();
            }

            public function getRoleNames(): array
            {
                return [];
            }

            public function getUser(): ?UserInterface
            {
                return $this->user;
            }

            public function setUser(UserInterface $user): void
            {
            }

            public function eraseCredentials(): void
            {
            }

            public function getAttributes(): array
            {
                return [];
            }

            public function setAttributes(array $attributes): void
            {
            }

            public function hasAttribute(string $name): bool
            {
                return false;
            }

            public function getAttribute(string $name): mixed
            {
                return $name;
            }

            public function setAttribute(string $name, mixed $value): void
            {
            }

            public function __serialize(): array
            {
                return [];
            }

            public function __unserialize(array $data): void
            {
            }
        };
    }
}
