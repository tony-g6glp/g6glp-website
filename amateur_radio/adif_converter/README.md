Contest Wizard Guide
====================

Purpose
-------

The Contest Wizard creates a contest definition used by the
ADIF to Cabrillo converter.

A contest definition contains three separate parts:

1. Contest Headers
2. Station Fields
3. QSO Layout


1. Contest Headers
==================

Database table:
    contest_headers

Purpose:
--------
Defines the Cabrillo header information.

These values appear once at the start of the Cabrillo file.

Example:

START-OF-LOG: 3.0
CONTEST: RSGB DATA
CALLSIGN: G6GLP
NAME: Tony Rider
CATEGORY-POWER: LOW


Fields:

Header Name
-----------
The Cabrillo field name.

Example:
CALLSIGN


Source Field
------------
The internal station field supplying the value.

Example:
callsign


Sort Order
----------
Controls the order in the Cabrillo header.



2. Station Fields
=================

Database table:
    contest_fields

Purpose:
--------
Defines information entered once by the operator.

These fields appear on the public generator form.


Examples:

Callsign
Operator Name
Power
Category
Locator


Fields:

Label
-----
Text displayed to the user.

Example:
Power


Field Name
----------
Internal name used by the program.

Example:
power


Field Type
----------
Available types:

text
select
checkbox


Required
--------
Determines whether the operator must enter a value.


Sort Order
----------
Controls display order.



3. QSO Layout
=============

Database table:
    contest_qso_fields

Purpose:
--------
Defines how each contact is written to Cabrillo.

Each QSO produces one output line.


Example:

QSO:
80M CW 20260101 120000 G6GLP 599 001 M0ABC 599 002


Fields:

Position
--------
Order in the QSO line.


Field Name
----------
Output description.


Source Field
------------
ADIF source field.

Examples:

BAND
MODE
QSO_DATE
TIME_ON
CALL
RST_SENT
RST_RCVD
STX
SRX


Field Width
-----------
Fixed width output size.


Alignment
----------
left
right


Default Value
-------------
Used when no source value exists.



Contest Activation
==================

A contest is not visible to users until activated.

Workflow:

New Contest
    |
    v
Build Definition
    |
    v
Review
    |
    v
Activate
    |
    v
Available in Public Converter



Testing Checklist
=================

Before activating:

[ ] Header fields complete
[ ] Station fields complete
[ ] QSO layout complete
[ ] Test ADIF uploaded
[ ] Cabrillo output checked
[ ] Contest rules verified