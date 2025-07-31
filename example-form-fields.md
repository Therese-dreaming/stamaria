# Example: Setting up Conditional Form Fields

## Scenario: Marriage Certificate Upload
You want to show a "Marriage Certificate" upload field only when the user selects "Married" as their marital status.

## Step 1: Create the Trigger Field (Marital Status)

```
Field 1 (Trigger Field):
- field_name: marital_status
- label: Marital Status
- field_type: select (or radio)
- options: ["Single", "Married", "Divorced", "Widowed"]
- required: 1
- is_conditional: 0 (This is NOT conditional - it's the trigger)
- condition_field: null
- condition_value: null
- order: 1
```

## Step 2: Create the Conditional Field (Marriage Certificate)

```
Field 2 (Conditional Field):
- field_name: marriage_cert
- label: Marriage Certificate
- field_type: file
- options: []
- required: 1
- is_conditional: 1 (This IS conditional)
- condition_field: marital_status (References the field_name from Step 1)
- condition_value: Married (Shows only when marital_status = "Married")
- order: 2
```

## How it works in the booking form:

1. User sees "Marital Status" dropdown/radio buttons
2. If user selects "Married", the "Marriage Certificate" file upload field appears
3. If user selects anything else ("Single", "Divorced", "Widowed"), the certificate field stays hidden
4. The certificate field is only required when it's visible (when condition is met)

## Another Example: Children Information

```
Trigger Field:
- field_name: has_children
- label: Do you have children?
- field_type: radio
- options: ["Yes", "No"]
- required: 1
- is_conditional: 0

Conditional Field:
- field_name: children_details  
- label: Children Details
- field_type: textarea
- required: 1
- is_conditional: 1
- condition_field: has_children
- condition_value: Yes
```

## Key Points:
- The trigger field (`condition_field`) must exist and have the correct `field_name`
- The `condition_value` must exactly match one of the trigger field's possible values
- Conditional fields can also be conditional on other conditional fields (nested conditions)
- Always create the trigger field first, then the conditional field
