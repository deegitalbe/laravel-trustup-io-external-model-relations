---
"@deegitalbe/laravel-trustup-io-external-model-relations": minor
---

Add loadMissingExternalRelations to model and collection

Introduce a memoized counterpart to loadExternalRelations, mirroring Eloquent's load / loadMissing. loadExternalRelations stays eager (always refetches); loadMissingExternalRelations loads only what is not already present. On a model it skips relation names already set; on a collection it resolves each relation only against the members missing it, leaving already loaded members untouched and never refetching them, matching Illuminate\Database\Eloquent\Collection::loadMissing. The methods operate on flat external relation names, so nesting composes as before: consumers resolve leaf models per depth and call the flat load on the leaf collection at any nesting level. Both new methods are added to the corresponding contracts.
