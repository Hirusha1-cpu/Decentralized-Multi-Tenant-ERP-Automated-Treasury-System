<?php

namespace App\Http\Controllers;

use Web3\Web3;
use Web3p\EthereumTx\Transaction;

class TenantPaymentController extends Controller
{
    public function deployContractFromLaravel()
    {
        // 1. අපේ Anvil Local Node එකට (හෝ Infura/Alchemy එකට) පාලම හැදීම
        $web3 = new Web3('http://127.0.0.1:8545'); 
        $eth = $web3->eth;

        // 2. Anvil එකෙන් අපිට නොමිලේ ලැබුණු 0වැනි ගිණුමේ Private Key එක 🔑
        $privateKey = '0xac0974bec39a17e36ba4a6b4d238ff944bacb478cbed5efcae784d7bf4f2ff80';
        $fromAddress = '0xf39Fd6e51aad88F6F4ce6aB8827279cffFb92266';

        // 3. ඔයා ටර්මිනල් එකේ දකිපු අර "input" Bytecode එක (SimpleStorage එකේ මැෂින් කෝඩ් එක) 🧱
        $contractBytecode = '0x608060405234801561001057600080fd5b5061011d806100206000396000f3fe...';

        // 4. EVM එක ඇතුළත ට්‍රාන්සැක්ෂන් පැකට් එක (Transaction Object) සකස් කිරීම
        // මතකද "to" කියන තැන null තියෙන්න ඕනේ contract හදද්දී! 
        $txParams = [
            'nonce'    => '0x0', // පළමු ගනුදෙනුව නිසා 0 (Hex වලින් 0x0)
            'gas'      => '0x87d89', // ඔයාගේ ටර්මිනල් එකේ පෙනුණු උපරිම ගෑස් සීමාව
            'gasPrice' => '0x3b9aca00', // 1 Gwei (Gas Price)
            'to'       => null, // 🔴 Contract Creation නිසා ලබන්නා NULL වේ!
            'data'     => $contractBytecode, // සැබෑ බයිට්කෝඩ් එක මෙතනට පාස් කරයි
            'value'    => '0x0' // සල්ලි යවන්නේ නැති නිසා 0 ETH
        ];

        // 🧠 [THE MAGIC] ලාරාවෙල් සර්වර් එක ඇතුළේදීම Private Key එක පාවිච්චි කරලා ගනුදෙනුව අත්සන් කිරීම!
        $tx = new Transaction($txParams);
        $signedTx = '0x' . $tx->sign($privateKey); // ගනුදෙනුව ආරක්ෂිතව අත්සන් විය

        // 🚀 [THE BROADCAST] අත්සන් කරපු සැබෑ ගනුදෙනුව Infura/Anvil වෙතට තල්ලු කිරීම!
        $eth->sendRawTransaction($signedTx, function ($err, $txHash) {
            if ($err !== null) {
                return response()->json(['error' => $err->getMessage()]);
            }

            // බ්ලොක්චේන් එකෙන් අපිට ලැබෙන සැබෑ Receipt ID (Transaction Hash) එක 🎉
            // උදා: 0x092f2c066a40987ec28128c6be5f7857fb124168620b3c9d7082538201770f8a
            return response()->json([
                'success' => true,
                'message' => 'Contract Deployment Transaction Broadcasted Successfully!',
                'transaction_hash' => $txHash 
            ]);
        });
    }
}
