# Introduction

CoreBank414 API v4.15.01 - A comprehensive API for financial services, government payments, utilities, and value-added services in Rwanda.

<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>

    This documentation provides comprehensive guidance for integrating with the CoreBank414 API, enabling seamless access to financial transactions, government services, utilities payments, and more.

    <aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
    You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

    ## Base URL
    The base URL for all endpoints is: `{{ config("app.url") }}/api/v.4.15.01`

    ## Glossary
    - **LTSS**: Long-Term Support Services
    - **VAS**: Value-Added Services
    - **RSSB**: Rwanda Social Security Board
    - **RRA**: Rwanda Revenue Authority
    - **RNIT**: Rwanda National Identification Agency
    - **CBHI**: Community-Based Health Insurance

    ## Status Codes
    ### Global HTTP Status Codes
    - **200 OK**: Request successful
    - **400 Bad Request**: Invalid request data
    - **401 Unauthorized**: Authentication required
    - **404 Not Found**: Resource not found
    - **500 Internal Server Error**: Server error

    ### Application-Specific Response Codes
    - **100**: SUCCESS
    - **101**: User status issue
    - **102**: Password temporarily blocked
    - **103**: Invalid authentication
    - **104**: FAILURE
    - **105**: Data validation error
    - **106**: Insufficient balance
    - **107**: General failure

