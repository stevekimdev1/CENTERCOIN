pragma solidity ^0.5.0;

import "./KIP37.sol";
import "./KIP37Burnable.sol";
import "./KIP37Pausable.sol";
import "./KIP37Mintable.sol";

contract KIP37Token is KIP37, KIP37Burnable, KIP37Pausable, KIP37Mintable {
    address payable public owner;

    mapping(uint256 => mintResultInfo) public mintResult;

    struct mintResultInfo {
        uint256 itemIdx;
        uint256 itemNum;
        string itemExpense;
        bytes32 hash;
        uint256 toeknId;
        string uri;
    }
    
    
    constructor(string memory uri) public KIP37(uri) {
        owner = msg.sender;
    }

    /// @notice Creates a new token type and assigns _initialSupply to the minter.
    /// @dev Throws if `msg.sender` is not allowed to create.
    ///   Throws if the token id is already used.
    /// @param _id The token id to create.
    /// @param _initialSupply The amount of tokens being minted.
    /// @param _uri The token URI of the created token.
    /// @return A boolean that indicates if the operation was successful.
     function onMint(
        uint256 _id,
        address _to,
        uint256 _initialSupply,
        string memory _uri,
        //bytes32 _hash,
        uint256 _itemIdx,
        uint256 _itemNum,
        string memory _itemExpense
    ) public payable returns (bool) {
        require(!_exists(_id), "KIP37: token already created");
        require(_to == msg.sender, "address incorrect");
        bytes32 _keccakHash = keccak256(abi.encodePacked(_uri));
        //require(_hash == _keccakHash, "hash incorrect");


        bytes32 hash = keccak256(abi.encodePacked(_id,_keccakHash,_itemExpense));
        creators[_id] = msg.sender;

        _mint(msg.sender, _id, 1, "");

        mintResultInfo memory Info = mintResultInfo(_itemIdx, _itemNum,_itemExpense, hash, _id, _uri);
        mintResult[_id] = Info;

        if (bytes(_uri).length > 0) {
            _uris[_id] = _uri;
            emit URI(_uri, _id);
        }
    }

    /*
    /// @notice Mints multiple KIP37 tokens of the specific token types `_ids` in a batch and assigns the tokens according to the variables `_to` and `_values`.
    /// @dev Throws if `msg.sender` is not allowed to mint.
    ///   MUST emit one or more `TransferSingle` events or a single `TransferBatch` event.
    ///   MUST revert if the length of `_ids` is not the same as the length of `_values`.
    /// @param _to The address that will receive the minted tokens.
    /// @param _ids The list of the token ids to mint.
    /// @param _values The list of quantities of tokens being minted.
    function onMintBatch(
        address _to,
        uint256[] memory _ids,
        uint256[] memory _values,
        string memory _uri,
        bytes32 _hash
    ) public payable returns (bool){
        for (uint256 i = 0; i < _ids.length; ++i) {
            require(!_exists(_ids[i]), "KIP37: token already created");
        }
        require(_to == msg.sender, "address incorrect");
        require(msg.sender.balance >= msg.value, "balance Insufficient");

        bytes32 _keccakHash = keccak256(abi.encodePacked(_uri));
        require(_hash == _keccakHash, "hash incorrect");

        
        owner.transfer(msg.value);

        for (uint256 i = 0; i < _ids.length; ++i) {
        creators[_ids[i]] = msg.sender;
        }

        _mintBatch(msg.sender, _ids, _values, "");

        for (uint i=0; i<_ids.length; ++i){
            if (bytes(_uri).length > 0) {
                _uris[_ids[i]] = _uri;
                emit URI(_uri, _ids[i]);
        }
        }
    }
    */

    /* NFT가 파는사람의 소유인지 확인하는 함수 */
    function _ownerOf(address _sellerAddress,uint256 tokenId) private view returns (bool) {
        return balanceOf(_sellerAddress, tokenId) != 0;
    }


    /* 구매자에게 NFT 소유권 이전 및 가격을 지불하는 함수 */
    function transferToken(uint256 _tokenId, uint256 _amount, address _sellerAddress, bytes memory data, bytes32 _hash, string memory _itemExpense) public {
        bytes32 _keccakHash = keccak256(abi.encodePacked(_tokenId, data, _itemExpense));
        require(_hash == _keccakHash, "hash incorrect");
        require(balanceOf(_sellerAddress, _tokenId) >= _amount,"The number of tokens has been exceeded." );
        require(_ownerOf(_sellerAddress, _tokenId), "Owner mismatch.");
        safeTransferFrom(_sellerAddress, msg.sender, _tokenId, _amount, data); 
    }

    /* 내 NFT 가져오기 */
    function importTransferToken(uint256 _tokenId, uint256 _amount, address _myAddress, bytes memory data/*, bytes32 _hash*/) public {
        
        require(balanceOf(msg.sender, _tokenId) >= _amount,"The number of tokens has been exceeded." );
        require(_ownerOf(msg.sender, _tokenId), "Owner mismatch.");
        safeTransferFrom(msg.sender, _myAddress, _tokenId, _amount, data); 
    }

    /* 구매자에게 NFT 소유권 이전 및 가격 지불 Batch */
    /*function transferTokenBatch(uint256[] memory _tokenIds, uint256[] memory _amounts, address _sellerAddress, bytes memory data, bytes32[] memory _hash) public {
        //require(msg.sender.balance > msg.value,"Insufficient balance.");  
        
        for(uint i = 0; i<_tokenIds.length; i++){
            bytes32 _keccakHash = keccak256(abi.encodePacked(_tokenIds[i], _sellerAddress));
            require(_hash[i] == _keccakHash, "hash incorrect");
            require(_ownerOf(_sellerAddress, _tokenIds[i]), "Owner mismatch.");
        }

        safeBatchTransferFrom(_sellerAddress, msg.sender, _tokenIds, _amounts, data);
       
        //_sellerAddress.transfer(msg.value);
        
    }*/

    function transferKlay() public payable returns (bool){
        require(msg.sender.balance >= msg.value, "balance Insufficient");
        owner.transfer(msg.value);
    }

   //보상받기
    function rewardTransferKlay(address _accountAddress) public payable returns (bool){
        require(_accountAddress.balance >= msg.value, "balance Insufficient");
        msg.sender.transfer(msg.value);
    }

}
