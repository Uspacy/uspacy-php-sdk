<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Invitations service.
 *
 * Mirrors the JS SDK's InvatesService under `/company/v1`.
 */
class InvatesService extends Service
{
    private const NAMESPACE = '/company/v1';

    /**
     * Check whether an email already has an invitation.
     */
    public function checkInvateByEmail(string $email): Response
    {
        return $this->http->get(self::NAMESPACE . '/users/check_invite', ['email' => $email]);
    }

    /**
     * Create invitations.
     *
     * @param  array  $body  list of invitation payloads
     */
    public function createInvates(array $body): Response
    {
        return $this->http->post(self::NAMESPACE . '/invites/email', $body);
    }

    /**
     * Create invitations in batch from a list of emails.
     *
     * @param  array  $emails  list of email addresses
     */
    public function createInvatesBatch(array $emails): Response
    {
        return $this->http->post(self::NAMESPACE . '/invites/email/batch', $emails);
    }

    /**
     * Resend an invitation to a user.
     *
     * @param  int|string  $userId
     */
    public function resendInvateByUserId($userId): Response
    {
        return $this->http->patch(self::NAMESPACE . "/invites/email/{$userId}/repeat_invitation");
    }

    /**
     * Delete an invitation by user id.
     *
     * @param  int|string  $userId
     */
    public function deleteInvateByUserId($userId): Response
    {
        return $this->http->delete(self::NAMESPACE . "/invites/email/{$userId}");
    }
}
