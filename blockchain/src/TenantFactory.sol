// SPDX-License-Identifier: MIT
pragma solidity ^0.8.18;

import "./TenantLedger.sol";

contract TenantFactory {
    mapping(string => address) public tenantToLedgerAddress;

    event TenantCreated(string tenantId, address ledgerAddress);

    function deployTenantLedger(string memory _tenantId) public {
        TenantLedger newLedger = new TenantLedger(_tenantId, msg.sender);
        tenantToLedgerAddress[_tenantId] = address(newLedger);
        emit TenantCreated(_tenantId, address(newLedger));
    }
}
