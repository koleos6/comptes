Accounts (Comptes) 
 ==============================

Description
 -----------

This plugin is used to manage Bank accounts (manually).

Plugin configuration
========================

In the plugin configuration page, 1st step is to activate it. 

Once the plugin is activated, specific mysql tables will be automatically created. 

The plugin configuration page allow to configure the following important data:
- - The possibility to import default categories in order to avoid to create the full set manually. 
It is a subset of the categories I used. It is up to you to modify them afterward.
To import the default categories, you need to clic on the "import" button. But be carrefull, if you have existing categories, conflicts could occcur (no advanced conflict management implemented). 

- - The name of the banks that could be associated to an acccount.
For every bank, a big and a small logo (for the menu) and a name could be set.

![comptes image configuration](../images/PageConfiguration.png)

1st line of the table allows to add a new bank.

Logos need to be put in "plugins/comptes/images/banques/" directory.

Recommended size for logos is 225 px * 225 px.

Recommended size for small logos is 40 px * 40 px.


Plugin management area.
=========
Go to *Plugins &gt; Organization* to find the plugin management page.

Here is an overview of this page with different accounts created:

![comptes image page plugin](../images/PagePlugin.png)

To the left, you can found the list of accounts already created along with a research field.

On the right, the list of all the accounts is displayed.

Once an account have been selected, you can update the account parameters:

![comptes image page plugin](../images/PagePluginDetail.png)

You can configure the following elements: 

- Name of the account

- Parent object
It is recommended to create one object per person and to attach the different accounts to the person object it bellongs. 

- Activate
Allows you to activate the account and have it available in the panel part.

- Visible
Will make the account visible on the dashboard

- Historize the account balance
Allow to generate, once a day, the balance of the account in Jeedom history section.

- Currency
Allow you to set the account currency type (€, $....)

- Display the un-checked operations
Default configuration for account panel display: possibility to show or don't show the un-checked operations

- Display the checked operations
Default configuration for account panel display: possibility to show or don't show the checked operations

- Option "Activation of the information Operation Type option": allow you to set the type of an operation (card, transfer, bank check)

- Option "Activation of the option that allow you to use only one date per operation instead of one operation date and one value date"

- Bank name
On the right part, a menu allows you to select the bank.
Once selected, the bank logo appears.

- Icon: allow you to choose an icon for the bank account: it will be use to differenciate bank accounts in the panel accounts list.

Dashboard widget
===========

It allows you to display: 
- the bank account balance and in parenthesis, the amount of operations to check (if the option is activated)
- the bank account balance at the end of the month (is the option is activated): it take into account the un-checked operation with a value date prior to the end of current month and the automatic transfer that are configured to happen prior to the end of the month.

Panel Comptes
===========

Go to *Home &gt; Comptes* to find the plugin panel, main content of the plugin.

An overview of the panel home: 
![comptes image panel acceuil](../images/PanelCompte_Accueil.png)

Two parts: The Management and The Active accounts

In the management part, the button "Update the accounts history", allow to launch the update without waiting for the automatic update.

The other functionalities are described hereafter. 

Categories management
-----------
This page allow you to manage the categories used for bank operations.
![comptes image panel categories](../images/PanelCompte_Categories.png)

It allows you to create categories and to "create" an icon for it. To do that, you can choose an icon in the list proposed by Jeedom and this plugin, you will have a lot of choices. 
After the icon choice, you can choose the background color and the icon color. 
To finish, you need to set a name and a level to the category. The level allow you to create sub-categories.

To organize the categories, you need to "slip" them with the mouse.

It is dynamic editing, so you can modify any fields, it will be updated once finished.

Bank acccount operations management
-----------
Once the bank account is selected, the list of all operations appears.

![comptes image panel operation defaut](../images/PanelCompte_Op1.png)

In the exemple above, you can observe 7 operations. 6 checked operations and 1 un-checked. You can see that the bank account balance and the "to check" are dependant to the checked and un-checked operations.
This allow you to follow on a day-to-day basis, your bank account operations by adding them in jeedom. 
Once the operation is visible on your bank account (website), you can "checked" it by clicking on the red arrow. 
Don't forget to update the Value date.

To add an operation, you need to set the different information required when you clic on the big "+", then you can click on "Enter" or on the "Add the operation" button. 

![comptes image panel operation ajout](../images/PanelCompte_Op2.png)

In addition or editing mode, once you clic on the category icon, a modal shows'up to choose the category:

![comptes image panel operation catégories](../images/PanelCompte_Op3.png)

To edit an operation, you need to clic on it, a modal appears bellow to edit the fields:

![comptes image panel operation edition](../images/PanelCompte_Op4.png)

Automatic transfer management
-----------
The next screen allow you to manage automatic transfer:

![comptes image panel virement auto](../images/PanelCompte_VirAuto.png)

The different fields are explicit. The objective is to avoid to have to add these operation manually. And it also allow to have them taken into account in the "End of month" balance in order to better manage your money.

Bank account transfer management
-----------

This last page allow you to perform a transfer between two bank accounts. You set it once and it will be added to each bank account. 

![comptes image panel transfert](../images/PanelCompte_Transfert.png)
