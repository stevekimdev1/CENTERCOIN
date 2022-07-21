// SPDX-License-Identifier:MIT
pragma solidity 0.8.13;

library SafeMath {
    
    function add(uint256 a, uint256 b) internal pure returns (uint256) {
        uint256 c = a + b;
        require(c >= a, "SafeMath: addition overflow");
        return c;
    }
    function sub(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b <= a, "SafeMath: subtraction overflow");
        uint256 c = a - b;
        return c;
    }
    function mul(uint256 a, uint256 b) internal pure returns (uint256) {
       if (a == 0) {
            return 0;
        }
        uint256 c = a * b;
        require(c / a == b, "SafeMath: multiplication overflow");
        return c;
    }
    function div(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b > 0, "SafeMath: division by zero");
        uint256 c = a / b;
        return c;
    }
    function mod(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b != 0, "SafeMath: modulo by zero");
        return a % b;
    }
}

interface IERC20 {
    function balanceOf(address account) external view returns(uint256);
    function transfer(address receiver, uint256 tokenAmount) external  returns(bool);
    function transferFrom( address tokenOwner, address recipient, uint256 tokenAmount) external returns(bool);
}

abstract contract Context {
    function _msgSender() internal view virtual returns (address) {
        return msg.sender;
    }
    function _msgData() internal view virtual returns (bytes calldata) {
        return msg.data;
    }
}

abstract contract Ownable is Context {
    address private _owner;
    event OwnershipTransferred(address indexed previousOwner, address indexed newOwner);
    constructor() {
        _setOwner(_msgSender());
    }
    function owner() public view virtual returns (address) {
        return _owner;
    }
    modifier onlyOwner() {
        require(owner() == _msgSender(), "Ownable: caller is not the owner");
        _;
    }
    function renounceOwnership() public virtual onlyOwner {
        _setOwner(address(0));
    }
    function transferOwnership(address newOwner) public virtual onlyOwner {
        require(newOwner != address(0), "Ownable: new owner is the zero address");
        _setOwner(newOwner);
    }
    function _setOwner(address newOwner) private {
        address oldOwner = _owner;
        _owner = newOwner;
        emit OwnershipTransferred(oldOwner, newOwner);
    }
}


contract Airdrop is Ownable {
    
    using SafeMath for uint256;
    IERC20 public Berry;
    event EtherTransfer(address beneficiary, uint amount);
    event TokenTransfer(address beneficiary, uint amount);

    constructor(IERC20  BCB_Token)  {
        Berry = BCB_Token;
    }
 
    function dropTokens(address[] memory _recipients, uint256 _amount) public onlyOwner {
       
        for (uint256 i = 0; i < _recipients.length; i++) {
            require(_recipients[i] != address(0));
            require(Berry.transfer(_recipients[i], _amount));

            emit TokenTransfer(_recipients[i], _amount);
        }
    }

    function dropEther(address[] memory _recipients, uint256[] memory _amount) public payable onlyOwner {
        uint total = 0;
        for(uint256 j = 0; j < _amount.length; j++) {
            total = total.add(_amount[j]);
        }
        require(total <= msg.value);
        require(_recipients.length == _amount.length);
        for (uint256 i = 0; i < _recipients.length; i++) {
            require(_recipients[i] != address(0));
            payable(_recipients[i]).transfer(_amount[i]);
            emit EtherTransfer(_recipients[i], _amount[i]);
        }
    }

    function withdrawTokens() public onlyOwner {
        require(Berry.transfer(owner(), Berry.balanceOf(address(this))));
    }

    function withdrawEther() public onlyOwner {
        payable(owner()).transfer(address(this).balance);
    }

    function getbalance() public view returns(uint256){
        return Berry.balanceOf(address(this));
    }

    // 0xD364DE0683B29E582e5713425B215b24Ce804Ae9 centre coin
}
