<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [

        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/auth-session',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/cbhi/rest/v.4.14.01/cbhi-payment-individual-client',   
        'https://agentapi.mobicash.rw/api/agent/goverment-services/rra/rest/v.4.14.01/payment-individual-client',
        'https://agentapi.mobicash.rw/api/agent/utilities/cbhi/rest/v.4.14.01/year-of-collection',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/cbhi/rest/v.4.14.01/nid-validation',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/cbhi/rest/v.4.14.01/payment',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/rra/rest/v.4.14.01/doc-id-validation',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/rra/rest/v.4.14.01/payment',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/ltss/rest/v.4.14.01/identification-validation',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/ltss/rest/v.4.14.01/payment',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/rnit/rest/v.4.14.01/identification-validation',
        'https://agentapi.mobicash.rw/api/agent/goverment-services/rnit/rest/v.4.14.01/payment',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/auth',
        'https://agentapi.mobicash.rw/api/agent/utilities/user/rest/v.4.14.01/account-balance',
        'https://agentapi.mobicash.rw/api/agent/vas/electricity/rest/v.4.14.01/meter-number-validation',
        'https://agentapi.mobicash.rw/api/agent/vas/electricity/rest/v.4.14.01/payment',
        'https://agentapi.mobicash.rw/api/agent/utilities/user/rest/v.4.14.01/all-transacion-by-id',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/change-password',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/forgetten-password-request',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/forgetten-password-change',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/dependant-client-enrollment',
        'https://agentapi.mobicash.rw/api/ria/service/rest/v.4.14.01/individual-clients-enrollment',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/token-ssession-activation',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/reset-user-account-pin',
        'https://agentapi.mobicash.rw/api/agent/user/rest/v.4.14.01/change-user-account-pin',
        'https://agentapi.mobicash.rw/api/ria/service/rest/v.4.14.01/Ria-Remitance-Order-Transfer',
        'https://agentapi.mobicash.rw/api/ria/service/rest/v.4.14.01/Ria-Remitance-Client-Withdraw',
        'https://agentapi.mobicash.rw/api/ria/service/rest/v.4.14.01/ria-Remitance-Client-Withdraw-Auhorization',
    ];
}
