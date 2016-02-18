# Script for pulling down tweets (currently by hash value) anc caching the results for a period of time
The get_bearer_token.php script is only to initially acquire a bearer token, which will be used in the actually fetching script.  Afterwards, it's not necessary unless the token must be reacquired.  Once received it's stored in an environment var.
