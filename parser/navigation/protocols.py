import navigation.source as src
from typing import Protocol 


class ElementFactory(Protocol):
    def create_element(self, source: src.ElementXPath):
        """Creates page element"""

class PageFactory(Protocol):
    def create_page(self, factory: ElementFactory):
        """Creates page"""

class AnchorElement(Protocol):
    def wait(self):
        """Element to wait until page loads"""
