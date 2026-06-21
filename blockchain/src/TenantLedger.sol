// SPDX-License-Identifier: MIT
pragma solidity ^0.8.18;

import "@chainlink/contracts/src/v0.8/shared/interfaces/AggregatorV3Interface.sol";

contract TenantLedger {
    address public immutable i_factoryOwner;
    string public tenantId;
    
    // Sepolia Testnet ETH/USD Price Feed Address 📈
    AggregatorV3Interface internal priceFeed = AggregatorV3Interface(0x694AA1769357215DE4FAC081bf1f309aDC325306);

    // EVM Event: සල්ලි ආපු සැනින් ලාරාවෙල් එකට මේ පණිවිඩය නිකුත් කරයි 🔔
    event InvoicePaid(uint256 indexed invoiceId, address indexed worker, uint256 amountInWei);

    constructor(string memory _tenantId, address _owner) {
        tenantId = _tenantId;
        i_factoryOwner = _owner;
    }

    // 💰 ඩොලර් අගය මත පදනම්ව සල්ලි (ETH) බාරගන්නා Payable Function එක
    function payInvoice(uint256 _invoiceId, uint256 _usdAmount) public payable {
        uint256 ethPrice = getLatestPrice();
        // ඩොලර් අගය Wei වලට හරවන සරල සමීකරණය (No Decimals)
        uint256 requiredEthInWei = (_usdAmount * 1e26) / ethPrice; 

        // හොරෙන් සල්ලි අඩුවෙන් එවන්න හැදුවොත් REVERT කරයි 🛑
        require(msg.value >= requiredEthInWei, "Insufficient ETH sent for this invoice!");

        // Event එක ලෝකෙටම නිකුත් කිරීම ⚡
        emit InvoicePaid(_invoiceId, msg.sender, msg.value);
    }

    function getLatestPrice() public view returns (uint256) {
        (, int256 price, , , ) = priceFeed.latestRoundData();
        return uint256(price * 1e10);
    }

    // සේප්පුවේ තියෙන සල්ලි ඔක්කොම මව් සමාගමට විද්‍රෝ කරගැනීම
    function withdraw() public {
        payable(i_factoryOwner).transfer(address(this).balance);
    }
}
