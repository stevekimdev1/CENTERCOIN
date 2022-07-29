pragma solidity ^0.5.0;
pragma experimental ABIEncoderV2; 

import "./ERC721Full.sol";
import "./ERC721Mintable.sol";
import "./ERC721MetadataMintable.sol";
import "./ERC721Burnable.sol";
import "./ERC721Pausable.sol";


contract ERC721Token is ERC721Full, ERC721Mintable, ERC721MetadataMintable, ERC721Burnable, ERC721Pausable  {

    address payable public owner;

    event mintResult(uint256 tokenId, uint256 itemIdx, uint256 itemNum, string itemExpense, bytes32 hash, string tokenURI);

 
    constructor() ERC721Full("GrandFisher", "GF") public {
        owner = msg.sender;
    }

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

    function importTransferToken(uint256[] memory _tokensId, address _myAddress, bytes[] memory data) public {
        for (uint256 i; i < _tokensId.length; i++) { 
        require(ownerOf(_tokensId[i]) == msg.sender, "Owner mismatch.");
        safeTransferFrom(msg.sender, _myAddress, _tokensId[i], data[i]);
        } 
    }

    function transferValue(address payable _reciveAddress) public payable {
        require(msg.sender.balance >= msg.value, "balance Insufficient");
        _reciveAddress.transfer(msg.value);
    }

    function onMint(uint256 tokenId, address to, string memory tokenURI, bytes32 _hash, bytes32 _data, uint256 _itemIdx, uint256 _itemNum, string memory _itemExpense) public {
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