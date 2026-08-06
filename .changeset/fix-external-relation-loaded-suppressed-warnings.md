---
"@deegitalbe/laravel-trustup-io-external-model-relations": patch
---

Fix externalRelationLoaded reporting a never-loaded relation as loaded when E_WARNING is suppressed

externalRelationLoaded() and getExternalModelRelationModels() detected whether a relation was already loaded by reading a possibly undefined array key inside Helpers::try, relying on the undefined-key warning being promoted to a throwable. Under runtimes where E_WARNING is not reported (observed with Laravel Octane on FrankenPHP, where error_reporting is lowered and not restored between requests), the access raises no throwable, so a never-loaded relation was reported as loaded. Any lazy load path (Model::loadMissing) then skipped the external load and the relation resolved to null. Both checks now use array_key_exists, which is independent of the runtime error_reporting level.
