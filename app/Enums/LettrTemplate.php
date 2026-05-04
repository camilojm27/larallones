<?php

declare(strict_types=1);

namespace App\Enums;

enum LettrTemplate: string
{
    case TeamInvitation = 'team-invitation';
    case ExpiredCard = 'expired-card';
    case InvoiceEmail = 'invoice-email';
    case PaymentDeclined = 'payment-declined';
    case SubscriptionCanceled = 'subscription-canceled';
    case SubscriptionCreated = 'subscription-created';
    case SubscriptionRenewal = 'subscription-renewal';
    case TrialExpired = 'trial-expired';
}
