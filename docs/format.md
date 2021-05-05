# Format Object

Format objects are read-only. Each format object has a child array of formats representing valid conversion targets. See the [Formats](formats.md) object for more information.

## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
name | getName() | string | The format name
credit_cost | getCreditCost() | integer | The credit cost of converting to this format. This will be null for the source format. Refer to the targets to see valid conversion targets and the associated credit cost.
targets | getTargets() | array of Format objects | An array of format objects representing the valid conversion formats and their associated credit cost.

## Helper Methods

Method | Returns | Description
-------|---------|-------------
getTargetsToCsv() | string | Returns a CSV string of valid conversion targets 

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.