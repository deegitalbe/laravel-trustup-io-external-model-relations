# @deegitalbe/laravel-trustup-io-external-model-relations

## 2.1.0

### Minor Changes

- dd095f1: Add loadMissingExternalRelations to model and collection

  Introduce a memoized counterpart to loadExternalRelations, mirroring Eloquent's load / loadMissing. loadExternalRelations stays eager (always refetches); loadMissingExternalRelations loads only what is not already present. On a model it skips relation names already set; on a collection it resolves each relation only against the members missing it, leaving already loaded members untouched and never refetching them, matching Illuminate\Database\Eloquent\Collection::loadMissing. The methods operate on flat external relation names, so nesting composes as before: consumers resolve leaf models per depth and call the flat load on the leaf collection at any nesting level. Both new methods are added to the corresponding contracts.

## 2.0.2

### Patch Changes

- 449a49c: Fix externalRelationLoaded reporting a never-loaded relation as loaded when E_WARNING is suppressed

  externalRelationLoaded() and getExternalModelRelationModels() detected whether a relation was already loaded by reading a possibly undefined array key inside Helpers::try, relying on the undefined-key warning being promoted to a throwable. Under runtimes where E_WARNING is not reported (observed with Laravel Octane on FrankenPHP, where error_reporting is lowered and not restored between requests), the access raises no throwable, so a never-loaded relation was reported as loaded. Any lazy load path (Model::loadMissing) then skipped the external load and the relation resolved to null. Both checks now use array_key_exists, which is independent of the runtime error_reporting level.

## 2.0.1

### Patch Changes

- afb5ba3: Add changeset release infrastructure + bun
