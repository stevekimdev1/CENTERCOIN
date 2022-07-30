pragma solidity ^0.5.0;
pragma experimental ABIEncoderV2;

import "./KIP17Full.sol";
import "./KIP17MetadataMintable.sol";
import "./KIP17Mintable.sol";
import "./KIP17Burnable.sol";
import "./KIP17Pausable.sol";

contract KIP17Token is KIP17Full, KIP17Mintable, KIP17MetadataMintable, KIP17Burnable, KIP17Pausable {
    address payable public owner;
    uint256[] private tokenList;
 
    constructor (string memory name, string memory symbol) public KIP17Full(name, symbol) {
         owner = msg.sender;
    }

    event mintResult(uint256 tokenId, uint256 itemIdx, uint256 itemNum, string itemExpense, bytes32 hash, string tokenURI);

    function transferToken(uint256 _tokenId, address _sellerAddress, bytes memory data, bytes32 _hash, string memory _itemExpense) public {
        bytes32 _keccakHash = keccak256(abi.encodePacked(data));
        require(_hash == _keccakHash, "hash incorrect");
        require(ownerOf(_tokenId)==_sellerAddress, "Owner mismatch.");
        safeTransferFrom(_sellerAddress, msg.sender, _tokenId, data); 
    }

    function transferTokenByAuction(uint256 _tokenId, address payable _sellerAddress, address _buyerAddress, bytes memory data, bytes32 _hash, string memory _itemExpense) public payable {
        bytes32 _keccakHash = keccak256(abi.encodePacked(data));
        require(_hash == _keccakHash, "hash incorrect");
        require(ownerOf(_tokenId)==_sellerAddress, "Owner mismatch.");
        safeTransferFrom(_sellerAddress, _buyerAddress, _tokenId, data); 
        _sellerAddress.transfer(msg.value);
    }

    function importTransferToken(uint256[] memory _tokensId, address _myAddress, bytes[] memory data/*, bytes32 _hash*/) public {
        for (uint256 i; i < _tokensId.length; i++) { 
        require(ownerOf(_tokensId[i]) == msg.sender, "Owner mismatch.");
        safeTransferFrom(msg.sender, _myAddress, _tokensId[i], data[i]);
        } 
    }

    function transferValue(address payable _reciveAddress) public payable returns (bool){
        require(msg.sender.balance >= msg.value, "balance Insufficient");
        _reciveAddress.transfer(msg.value);
    }

    function myTokenlist(address selectedAddress) public returns(uint256[] memory) {
        
        for (uint256 i; i < balanceOf(selectedAddress); i++) { 
           uint256 tokenId = tokenOfOwnerByIndex(selectedAddress, i);
           tokenList.push(tokenId);
           }
        return tokenList;
    }

    /*

    /**
     * @dev Function to mint tokens.
     * @param to The address that will receive the minted tokens.
     * @param tokenId The token id to mint.
     * @param tokenURI The token URI of the minted token.
     * @return A boolean that indicates if the operation was successful.
     */
    function onMint(uint256 tokenId, address to, string memory tokenURI, bytes32 _hash, bytes32 _data, uint256 _itemIdx, uint256 _itemNum, string memory _itemExpense) public returns (bool) {
        require(to == msg.sender, "address incorrect");
        require(_data == keccak256(abi.encodePacked(_hash)), "incorrect Hash value");

        _mint(to, tokenId);

        _setTokenURI(tokenId, tokenURI);
        
        bytes32 hash = keccak256(abi.encodePacked(_hash));
        
        emit mintResult(tokenId, _itemIdx, _itemNum, _itemExpense, hash, tokenURI);    
    }

    function buyAuctionItem(address payable _to) public payable {
        _to.transfer(msg.value);
    }
}    